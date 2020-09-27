<?php
$filename = $_SERVER['DOCUMENT_ROOT'].'/pgn/recullhistoric.pgn';
$finfo = new finfo(FILEINFO_MIME_TYPE);
echo mime_content_type($filename);