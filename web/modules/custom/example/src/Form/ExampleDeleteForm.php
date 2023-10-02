<?php declare(strict_types = 1);

namespace Drupal\example\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\example\Db;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a example form.
 */
class ExampleDeleteForm extends FormBase {

  /**
   * The db service.
   *
   * @var \Drupal\example\Db
   */
  protected $db;

  /**
   * Constructs a new example instance.
   *
   * @param \Drupal\example\Db $db
   *   The db service.
   */
  public function __construct(Db $db) {
    $this->db = $db;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('example.db')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'example_example_delete';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $results = $this->db->getAll();

    $header = [
      'id' => $this->t('ID'),
      'name' => $this->t('Name'),
      'delete' => $this->t('Delete'),
    ];

    $form['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#empty' => $this->t('No items found'),
    ];

    foreach ($results as $id => $row) {
      $form['table'][$id]['id'] = [
        '#markup' => $id,
      ];
      $form['table'][$id]['name'] = [
        '#markup' => $row->name,
      ];
      $form['table'][$id]['delete'] = [
        '#type' => 'submit',
        '#value' => $this->t('Delete'),
        '#name' => 'delete-' . $id, // Include the ID as part of the submit button name.
        '#submit' => ['::deleteItem'], // Define the submit handler for delete.
        '#ajax' => [
          'callback' => '::submitFormAjax', // Implement AJAX to refresh the table after deletion.
          'wrapper' => 'your-table-wrapper', // Specify the ID of the table container to refresh.
        ],
      ];

    }

    $form['#prefix'] = '<div id="your-table-wrapper">';
    $form['#suffix'] = '</div>';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
  }

  public function deleteItem(array &$form, FormStateInterface $form_state) {
    $triggering_element = $form_state->getTriggeringElement();
    $id = (int) str_replace('delete-', '', $triggering_element['#name']) ?? null;

    if (is_int($id)) {
      $this->db->deleteById($id);
    }

    $form_state->setRebuild();

  }

  public function submitFormAjax(array &$form, FormStateInterface $form_state) {
    return $form['table'];
  }

}
