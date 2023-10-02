<?php declare(strict_types = 1);

namespace Drupal\example\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

/**
 * Provides a phone block.
 *
 * @Block(
 *   id = "example_phone_block",
 *   admin_label = @Translation("Phone block"),
 *   category = @Translation("Custom"),
 * )
 */
class PhoneBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration(): array {
    return [
      'phone_number' => $this->t('01722 333333'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state): array {
    $form['phone_number'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Phone number'),
      '#default_value' => $this->configuration['phone_number'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state): void {
    $this->configuration['phone_number'] = $form_state->getValue('phone_number');
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {

    $config = $this->getConfiguration();

    $phone_number = $config['phone_number'] ?? '';


    // example one - simple markup with translation.
    return [
      '#markup' => $this->t('The phone number is @number!', ['@number' => $phone_number]),
    ];

    // example two - template with translation.
    /*
    return [
      '#theme' => 'telephone_display',
      '#telephone_number' => $phone_number,
      '#title' => '',
      '#cache' => [
        'contexts' => [
          'url.path',
        ],
      ],
    ];*/


    // example three - template with translation and cache tags.
    /*
    $node_title = Node::load(2)->getTitle() ?? '';
    return [
      '#theme' => 'telephone_display',
      '#telephone_number' => $phone_number,
      '#node_title' => $node_title,
      '#cache' => [
        'contexts' => [
          'url.path',
        ],
        'tags' => [
          'node:2',
        ],
      ],
    ];*/

  }

}
