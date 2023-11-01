<?php

namespace Drupal\dashboard_admin\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\Entity\NodeType;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Dashboard Controller.
 */
class DashboardController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public function __construct(AccountInterface $current_user) {
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function content() {
    return [
      '#theme' => 'admin_dashboard',
      '#roles_defined' => count($this->currentUser->getRoles()) > 1,
      '#items_content' => $this->menuOfTypeNodes(),
      '#items_medias' => $this->menuOfMedias(),
      '#items_others' => $this->menuOfOthers(),
      '#attached' => [
        'library' => ['dashboard_admin/dashboard'],
      ],
    ];
  }

  /**
   * Get menu of node types.
   *
   * @return array
   *   A list of node types.
   */
  private function menuOfTypeNodes() {
    $items_content = [];
    $entities = NodeType::loadMultiple();

    $exclude_nodes = [];
    // Sort by name.
    usort($entities, function ($item1, $item2) {
      return $item1->label() < $item2->label() ? -1 : 1;
    });

    foreach ($entities as $entity) {
      $entity_type_manager = \Drupal::entityTypeManager();
      $access_handler = $entity_type_manager->getAccessControlHandler('node');
      $access = $access_handler->createAccess($entity->id());

      if (in_array($entity->id(), $exclude_nodes) || !$access) {
        continue;
      }

      $items_content[] = [
        'title' => $entity->label(),
        'description' => $entity->getDescription(),
        'url' => Url::fromRoute('view.content.page_1', ['type' => $entity->id()])->toString(),
        'type' => $entity->id(),
      ];
    }

    return $items_content;
  }


  /**
   * Get menu of different media types.
   *
   * @return array
   *   A list of media types.
   */
  private function menuOfMedias() {
    $medias = [];

    if (!$this->currentUser->hasPermission('access media overview')) {
      return $medias;
    }

    // Load all media types from the database.
    $mediaTypes = \Drupal::entityTypeManager()
      ->getStorage('media_type')
      ->loadMultiple();

    // Loop through each media type and add to the list of medias.
    foreach ($mediaTypes as $mediaType) {
      $label = $mediaType->label();
      $machineName = $mediaType->id();

      $medias[] = [
        'title' => $this->t($label),
        'description' => $this->t('List of @type media items.', ['@type' => strtolower($label)]),
        'url' => Url::fromUri('internal:/admin/content/media', ['query' => ['type' => $machineName]])->toString(),
        'add_url' => Url::fromUri('internal:/media/add/' . $machineName)->toString(),
      ];

    }

    return $medias;
  }


  /**
   * Get menu of other forms, taxonomies.
   *
   * @return array
   *   A list of other menu items.
   */
  private function menuOfOthers() {
    $others = [];

    if ($this->currentUser->hasPermission('access user profiles')) {
      $others[] = [
        'title' => $this->t('Users'),
        'description' => $this->t('User list'),
        'url' => Url::fromRoute('view.user_admin_people.page_1')->toString(),
      ];
    }

    if ($this->currentUser->hasPermission('access webform overview')) {
      $others[] = [
        'title' => $this->t('Forms'),
        'description' => $this->t('List of forms and submissions'),
        'url' => Url::fromRoute('entity.webform.collection')->toString(),
      ];
    }

    if ($this->currentUser->hasPermission('access taxonomy overview')) {
      $others[] = [
        'title' => $this->t('Taxonomies'),
        'description' => $this->t('List of taxonomies'),
        'url' => '/dashboard_admin/structure/taxonomy',
      ];
    }

    if ($this->currentUser->hasPermission('access dashboard_admin dashboard')) {
      $others[] = [
        'title' => $this->t('Site settings'),
        'description' => $this->t('Basic site settings: contact page, homepage banner in logged-in mode'),
        'url' => Url::fromRoute('dashboard_admin.dashboard')->toString(),
      ];
    }

    return $others;
  }

}
