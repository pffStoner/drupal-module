<?php
namespace Drupal\student\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
/**
 * Class StudentForm.
 *
 * @package Drupal\student\Form
 */
class StudentForm extends FormBase {
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'student_form';
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $conn = Database::getConnection();
        $record = array();
        if (isset($_GET['num'])) {
            $query = $conn->select('student', 'm')
                ->condition('id', $_GET['num'])
                ->fields('m');
            $record = $query->execute()->fetchAssoc();
        }
        $form['candidate_name'] = array(
            '#type' => 'textfield',
            '#title' => t('Candidate Name:'),
            '#required' => TRUE,
            //'#default_values' => array(array('id')),
            '#default_value' => (isset($record['name']) && $_GET['num']) ? $record['name']:'',
        );
        //print_r($form);die();
        $form['faculty_number'] = array(
            '#type' => 'textfield',
            '#title' => t('Faculty Number:'),
            '#default_value' => (isset($record['facultynumber']) && $_GET['num']) ? $record['facultynumber']:'',
        );
        $form['candidate_mail'] = array(
            '#type' => 'email',
            '#title' => t('Email ID:'),
            '#required' => TRUE,
            '#default_value' => (isset($record['email']) && $_GET['num']) ? $record['email']:'',
        );
        $form['candidate_gender'] = array (
            '#type' => 'select',
            '#title' => ('Gender'),
            '#options' => array(
                'Female' => t('Female'),
                'male' => t('Male'),
                '#default_value' => (isset($record['gender']) && $_GET['num']) ? $record['gender']:'',
            ),
            /*
             * $form['choice'] = array(
                '#type' => 'radios',
                 '#title' => t('select ur domain'),
                '#description' => t('Specify ur site.'),
                '#options' => array(
                t('On this site'),
                t('On web'),
                )
                );*/
        );
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => 'save',
            //'#value' => t('Submit'),
        ];
        return $form;
    }
    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        $name = $form_state->getValue('candidate_name');
        if(preg_match('/[^A-Za-z]/', $name)) {
            $form_state->setErrorByName('candidate_name', $this->t('your name must in characters without space'));
        }
        if (strlen($form_state->getValue('faculty_number')) < 8 ) {
            $form_state->setErrorByName('faculty_number', $this->t('your faculty number must in 8 digits'));
        }
        parent::validateForm($form, $form_state);
    }
    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $field=$form_state->getValues();
        $name=$field['candidate_name'];
        //echo "$name";
        $number=$field['faculty_number'];
        $email=$field['candidate_mail'];
        $gender=$field['candidate_gender'];

        if (isset($_GET['num'])) {
            $field  = array(
                'name'   => $name,
                'facultynumber' =>  $number,
                'email' =>  $email,
                'gender' => $gender,
            );
            $query = \Drupal::database();
            $query->update('student')
                ->fields($field)
                ->condition('id', $_GET['num'])
                ->execute();
            drupal_set_message("succesfully updated");
            $form_state->setRedirect('student.display_table_controller_display');
        }
        else
        {
            $field  = array(
                'name'   =>  $name,
                'facultynumber' =>  $number,
                'email' =>  $email,
                'gender' => $gender,
            );
            $query = \Drupal::database();
            $query ->insert('student')
                ->fields($field)
                ->execute();
            drupal_set_message("succesfully saved");
            $response = new RedirectResponse("/addstudent");
            $response->send();
        }
    }
}