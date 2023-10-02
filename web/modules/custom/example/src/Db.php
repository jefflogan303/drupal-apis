<?php declare(strict_types = 1);

namespace Drupal\example;

use Drupal\Core\Database\Connection;

/**
 * This service provides methods to interact with the database.
 */
class Db {

  /**
   * Constructs a Db object.
   */
  public function __construct(
    private readonly Connection $connection,
  ) {}

  /**
   * Add name.
   */
  public function addName(string $name): void {
    $query = $this->connection->insert('example')
      ->fields([
        'name' => $name,
      ])
      ->execute();
  }

  /**
   * Delete item by id.
   *
   * @param int $id
   */
  public function deleteById(int $id):void {
    $query = $this->connection->delete('example')
      ->condition('id', $id)
      ->execute();
  }

  public function getAll() {
    $query = $this->connection->select('example', 'e')
      ->fields('e', ['id', 'name'])
      ->execute()
      ->fetchAllAssoc('id');
    return $query;
  }

}
