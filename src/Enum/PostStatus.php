<?php 
// src/Enum/PostStatus.php
namespace App\Enum;

enum PostStatus: string
{
    case Waiting = 'waiting';
    case Published = 'published';
    case Draft = 'draft';
}
