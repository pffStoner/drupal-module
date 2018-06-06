<?php
namespace Drupal\student\Plugin\Block;
use Drupal\Core\Block\BlockBase;
/**
 * Provides a 'StudentBlock' block.
 *
 * @Block(
 *  id = "student_block",
 *  admin_label = @Translation("Student block"),
 * )
 */
class StudentBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {

        $form = \Drupal::formBuilder()->getForm('Drupal\student\Form\StudentForm');
        return $form;
    }
}