<?php
namespace Plugins\Opoink\Liv\Http\Controllers\Admin\AdminListing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Plugins\Opoink\Liv\Lib\Facades\AdminListing;

class Bookmark extends Controller {


	public function saveVisibleColumns(Request $request){
		$data = $request->validate([
			"id" => "required|exists:listing_bookmark,id",
			"columns" => "required"
		]);

		AdminListing::saveVisibleColumns($data['id'], $data['columns']);

		return response()->json([
			'message' => 'success'
		], 200);
	}
}
?>