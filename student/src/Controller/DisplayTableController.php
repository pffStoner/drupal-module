<?php
namespace Drupal\student\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
/**
 * Class DisplayTableController.
 *
 * @package Drupal\student\Controller
 */
class DisplayTableController extends ControllerBase {
    public function getContent() {
        $build = [
            'description' => [
                '#theme' => 'student_description',
                '#description' => 'foo',
                '#attributes' => [],
            ],
        ];
        return $build;
    }
    /**
     * Display.
     *
     * @return string
     *   Return Hello string.
     */
    public function display() {
        /**return [
        '#type' => 'markup',
        '#markup' => $this->t('Implement method: display with parameter(s): $name'),
        ];*/
        //create table header
        $header_table = array(
            'id'=>    t('No'),
            'name' => t('Name'),
            'facultynumber' => t('Faculty_Number'),
            //'email'=>t('Email'),
            'gender' => t('Gender'),
            'opt' => t('operations'),
            'opt1' => t('operations'),
        );
//select records from table
        $query = \Drupal::database()->select('student', 'm');
        $query->fields('m', ['id','name','facultynumber','email','gender']);
        $results = $query->execute()->fetchAll();
        $rows=array();
        foreach($results as $data){
            $delete = Url::fromUserInput('/student/delete/'.$data->id);
            $edit   = Url::fromUserInput('addstudent?num='.$data->id);
            //print the data from table
            $rows[] = array(
                'id' =>$data->id,
                'name' => $data->name,
                'facultynumber' => $data->facultynumber,
                //'email' => $data->email,
                'gender' => $data->gender,
                \Drupal::l('Delete', $delete),
                \Drupal::l('Edit', $edit),
            );
        }
        //display data in site
        $form['table'] = [
            '#type' => 'table',
            '#header' => $header_table,
            '#rows' => $rows,
            '#empty' => t('No student data are added yet'),
        ];
        return $form;
    }
}