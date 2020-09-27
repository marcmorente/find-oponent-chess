<?php
ini_set('upload_max_filesize', '50M');
ini_set('post_max_size', '50M');
ini_set('max_input_time', 300);
ini_set('max_execution_time', 300);

require_once "../autoload.php";
$upload_pgn = new UploadPGN($_FILES['fileToUpload']);

try {

    if (!$upload_pgn->upload()) {
        throw new RuntimeException('Error important arxiu!');
    }

    $file_name    = $upload_pgn->getPgnFileName();
    $pgn_file     = '../../uploads/'.$file_name;
    $parser       = new PgnParser($pgn_file);
    $pgn          = $parser->getUnparsedGames();
    $db           = new MysqlDatabaseRepository();
    $persistGames = new PersistGames($pgn, $db);

    $persistGames->setPgn();

    file_put_contents('../../logs/log.txt', $file_name.PHP_EOL, FILE_APPEND);
    unlink($pgn_file);
    
    echo json_encode([
        "error" => false
    ]);
} catch (RuntimeException $e) {
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage()
    ]);
}
