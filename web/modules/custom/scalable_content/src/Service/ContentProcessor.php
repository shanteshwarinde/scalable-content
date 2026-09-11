namespace Drupal\scalable_content\Service;

class ContentProcessor {

  public function process(array $data): array {
    $data['title'] = trim($data['title'] ?? '');
    $data['description'] = trim($data['description'] ?? '');

    return $data;
  }

}
