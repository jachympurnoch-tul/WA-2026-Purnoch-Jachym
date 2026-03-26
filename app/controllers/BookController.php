<?php

class BookController {
    //výchozí metoda pro zobrazení úvodní stránky
    public function index(){
        require_once '../app/views/books/books_list.php'
    }
}