<?php
// save_result.php

// Lấy dữ liệu JSON từ AJAX
$data = json_decode(file_get_contents('php://input'), true);

$score = $data['score'] ?? 0;
$timeSpent = $data['timeSpent'] ?? 0;

// TODO: lưu dữ liệu vào CSDL, file, hoặc gửi email, v.v.
// Ở đây chỉ là lưu tạm thời vào file
$log = date('Y-m-d H:i:s') . " - Điểm: $score - Thời gian: $timeSpent giây\n";
file_put_contents('results.log', $log, FILE_APPEND);

echo json_encode(['status' => 'success']);
