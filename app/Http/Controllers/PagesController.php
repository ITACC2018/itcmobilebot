<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
	/**
	* Front page
	*/
	public function getIndex() {
		return view('pages.index');
	}

	/**
	* Show case page
	*/
	public function getShowCase() {
		return view('pages.showcase');
	}

	/**
	* Services page
	*/
	public function getServices() {
		return view('pages.showcase');
	}

	/**
	* Contact page
	*/
	public function getContact() {
		return view('pages.showcase');
	}
}