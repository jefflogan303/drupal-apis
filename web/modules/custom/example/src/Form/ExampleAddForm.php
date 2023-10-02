<?php declare(strict_types = 1);

namespace Drupal\example\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\example\Db;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a example add form.
 */
class ExampleAddForm extends FormBase {


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
    return 'example_add_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Add name'),
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {

    $name = $form_state->getValue('name');

    if (!empty($name)) {
      $database = \Drupal::database();
      $query = $database->insert('example')
        ->fields([
          'name' => $name,
        ])
        ->execute();
    }

    $this->messenger()->addStatus($this->t('The form has been submitted.'));
    $form_state->setRedirect('<front>');
  }

}
