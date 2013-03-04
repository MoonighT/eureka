<?php
require_once '../api/db_query.php';

function institution_autocomplete($query) {
    return search_institution($query);
}

function subject_autocomplete($query) {
    return search_subject($query);
}

$type = $_GET['type'];
$query = $_GET['query'];
$result = call_user_func($type . '_autocomplete', $query);
echo json_encode($result);
