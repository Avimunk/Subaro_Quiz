<?php
error_reporting(0);

$name = $_REQUEST['name'];
$email = ($_REQUEST['email']) ? $_REQUEST['email'] : 'test@gmail.com';
$phone = $_REQUEST['phone'];
$message_post = $_REQUEST['message'];
$results = $_REQUEST['results'];
$score_table = '<table width="100%" border="1" cellspacing="0" cellpadding="0">';
$score_table .= '<tr><th style="text-align:center; padding: 5px;"><strong>ID</strong></th><th style="text-align:center; padding: 5px;"><strong>Question</strong></th><th style="text-align:center; padding: 5px;"><strong>User\'s Answer</strong></th><th style="text-align:center; padding: 5px;"><strong>Is Correct?</strong></th></tr>';
foreach ($results as $result) {
	$score_table .= '<tr>';
	$is_correct = ($result['is_correct']==1) ? '&#9745;' : '&#9744;';
	$score_table .= '<td style="text-align:center; padding: 5px;">'.$result['question_id'].'</td>';
	$score_table .= '<td style="text-align:center; padding: 5px;">'.$result['question'].'</td>';
	$score_table .= '<td style="text-align:center; padding: 5px;">'.$result['answer'].'</td>';
	$score_table .= '<td style="text-align:center; padding: 5px;">'.$is_correct.'</td>';
	$score_table .= '</tr>';
}
$score_table .= '</table>';


$to      = 'sliding_quiz@mailmetrash.com';
$subject = 'New Submit Answer';
$message = '';
$message .= '<table width="100%" border="0" cellpadding="0" cellspacing="10">';
$message .= '<tr><td width="1%" nowrap="">Name:</td><td>&nbsp;&nbsp;'.$name.'</td></tr>';
$message .= '<tr><td width="1%" nowrap="">Email:</td><td>&nbsp;&nbsp;'.$email.'</td></tr>';
$message .= '<tr><td width="1%" nowrap="">Phone:</td><td>&nbsp;&nbsp;'.$phone.'</td></tr>';
$message .= '<tr><td width="1%" nowrap="">Message:</td><td>&nbsp;&nbsp;'.$message_post.'</td></tr>';
$message .= '</table><br>'.$score_table;


//print_r($message);
//exit;

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
$headers .= 'From: '.$email . "\r\n" .'Reply-To: '.$email;

mail($to, $subject, $message, $headers);

echo 1;
?>
