<?php
CakePlugin::load('MeioUpload');

class User extends AppModel {
	public $name = 'User';

	public $hasOne = array(
			'Student' => array('className' => 'Student', 'dependent' => 'true'),
			'Professor' => array('className' => 'Professor',
					'dependent' => 'true'),
			'Employee' => array('className' => 'Employee',
					'dependent' => 'true'));

	public $actsAs = array(
			'MeioUpload.MeioUpload' => array(
					'photo' => array('dir' => 'photos',
							'create_directory' => true,
							'allowed_mime' => array('image/jpeg',
									'image/pjpeg', 'image/png'),
							'allowed_ext' => array('.jpg', '.jpeg', '.png'),
							'thumbsizes' => array(
									'small' => array('width' => 120,
											'height' => 120)),
							'default' => 'default.jpg')));

	public function beforeSave() {
		if (isset($this->data['User']['password'])) {
			if (isset($this->data['User']['hash']) == false) {
				$this->data['User']['hash'] = substr(
						Security::hash($this->data['User']['nusp'] . time()),
						0, 40);
			}
			$this->data['User']['password'] = AuthComponent::password(
					$this->data['User']['password']);
		}
	}
}
