<?php

class BookController {
    //výchozí metoda pro zobrazení úvodní stránky
    public function index(){
        require_once '../app/controllers/views/books/books_list.php';
    }
}