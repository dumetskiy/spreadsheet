<?php

declare(strict_types=1);

namespace Spreadsheet\Enum;

enum FileSource: string
{
    use BaseEnumTrait;

    case FILE = 'file';
    case FTP = 'ftp';
    case HTTP = 'http';
    case HTTPS = 'https';
    case PHP = 'php';
    case DATA = 'data'; // Data (RFC 2397)
    case SSH2 = 'ssh2'; // Secure Shell 2
}
