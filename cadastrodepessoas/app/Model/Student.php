<?php
class Student extends AppModel {
	public $name = 'Student';

	public $belongsTo = array(
			'User' => array('className' => 'User', 'foreignKey' => 'user_id'),
			'Course' => array('className' => 'Course',
					'foreignKey' => 'course_id'));

	public function saveProfile($id, $data) {
		$data['Student']['user_id'] = $id;

		$this->save($data);
	}
}
