<?php
namespace Drupal\student\Controller;
use Drupal\Core\Controller\ControllerBase;
/**
 * Class StudentController.
 *
 * @package Drupal\student\Controller
 */
class StudentController extends ControllerBase {
    /**
     * Display.
     *
     * @return string
     *   Return Hello string.
     */
    public function display() {
        return [
            '#type' => 'markup',
            '#markup' => $this->t('This page contain nothing for now ')
        ];
    }
}