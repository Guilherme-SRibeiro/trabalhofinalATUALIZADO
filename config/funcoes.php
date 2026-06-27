<?php
function sanitize($conn, $str) {
    return $conn->real_escape_string(trim(strip_tags($str)));
}

function resumo($texto, $limite = 180) {
    $texto = strip_tags($texto);
    if (mb_strlen($texto) <= $limite) return $texto;
    return mb_substr($texto, 0, $limite) . '…';
}

function formataData($data) {
    $dt = new DateTime($data);
    $meses = ['jan','fev','mar','abr','mai','jun','jul','ago','set','out','nov','dez'];
    return $dt->format('d') . ' ' . $meses[(int)$dt->format('n') - 1] . '. ' . $dt->format('Y') . ' · ' . $dt->format('H:i');
}

function usuarioLogado() {
    return isset($_SESSION['usuario_id']);
}

function nomeUsuario() {
    return $_SESSION['usuario_nome'] ?? '';
}
