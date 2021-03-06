﻿<?php
require_once '../includes/config.php';
$id = $_POST['id'];
$securitycode = $_POST['securitycode'];
$useragent = $_SERVER['HTTP_USER_AGENT'];

$nowTime = date("YmdHi");
$nowTime2 = substr($nowTime, 0, strlen($nowTime) - 1);
$securitycodeShouldBe = hash('sha512', $nowTime2 . $useragent . 'adminrequest', false);

if ($securitycode != $securitycodeShouldBe || $useragent != "System-" . SERVERNAME . "-adminrequest") {
    echo "Security Auth Fail";
    return;
}

$stmt = $db->prepare('SELECT * FROM members WHERE id = :id');
$stmt->execute(array(':id' => $id));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (empty($row['id'])) {
    echo "해당 아이디는 없습니다.";
    return;
} else {
    $stmt = $db->prepare("UPDATE members SET ComputerName1 = :ComputerName1,ComputerHard1 = :ComputerHard1 WHERE id = :id");
    $stmt->execute(array(
        ':id' => $id,
        ':ComputerName1' => "NULL",
        ':ComputerHard1' => "NULL",
    ));
    echo "정상처리 완료";
    return;
}
