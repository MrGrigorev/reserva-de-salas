<?php
	echo "<h1>" . $room['Room']['name'] . "</h1>"; 
?>
<br />
<?php
	echo __('Localização') . ": " . __('Bloco') . $room['Room']['building'];
	if ($room['Room']['number']) {
		echo ", " . __('Sala') . " " . $room['Room']['number'];
	}
	echo "<br />";
	echo "<br />";
	echo __('Capacidade') . ": " . $room['Room']['capacity'];
	
	if ($room['Room']['description']) {
		echo "<br />";
		echo "<br />";
		echo __('Descrição') . ":<br />";
		echo $room['Room']['description'];
	}
?>

<?php
	echo $this->element('back');
?>
