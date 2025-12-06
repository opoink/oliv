<?php
namespace Plugins\Opoink\Liv\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Plugins\Opoink\Liv\Lib\Facades\SystemConfig;
use Illuminate\Support\Facades\DB;

class Settings extends Controller  {

	/**
	 * Handle the incoming request.
	 */
	public function __invoke(Request $request)
	{
		$targetDir = storage_path(DS."app".DS."private".DS."plugins".DS."etc".DS."admin");
		$systemTabSectionFile = $targetDir . DS . "system_tab_section.php";

		$tabSection = [];
		if(file_exists($systemTabSectionFile)){
			$tabSection = include($systemTabSectionFile);
		}

		$selectedTab = "";

		if($request->tab){
			$selectedTab = $request->tab;
		}
		elseif(isset($tabSection[0])) {
			$selectedTab = $tabSection[0]['name'];
		}

		
		$selectedSection = "";
		if($request->section && $request->tab){
			$selectedSection = $request->section;
		}
		elseif(isset($tabSection[0])) {
			if( isset($tabSection[0]['children'][0]) && isset($tabSection[0]['children'][0]['name']) ){
				$selectedSection = $tabSection[0]['children'][0]['name'];
			}
		}

		if(!empty($selectedTab) && !empty($selectedSection)){
			$fieldGroupsFile = $targetDir . DS . "system_tab_section" . DS . $selectedTab.".".$selectedSection.".php";
			$fieldGroups = [];
			if(file_exists($fieldGroupsFile)){
				$fieldGroups = include($fieldGroupsFile);
				$fieldGroups = SystemConfig::assignValue($fieldGroups, $selectedTab."/".$selectedSection);
			}
		}

		return inertiaRender('Opoink/Liv/resources/js/Pages/Admin/Settings/Index', [
			'tab_section' => $tabSection,
			'selected' => [
				"tab" => $selectedTab,
				"section" => $selectedSection,
				"field_groups" => $fieldGroups,
			]
		]);
	}

	public function saveSettings(Request $request){
		$data = $this->validate($request, [
			'data' => 'required',
			'tab' => 'required',
			'section' => 'required',
		]);

		
		DB::beginTransaction();
		try {
			SystemConfig::saveSystemConfig($data['data']);	
			DB::commit();

			$selectedTab = $data['tab'];
			$selectedSection = $data['section'];
			
			$targetDir = storage_path(DS."app".DS."private".DS."plugins".DS."etc".DS."admin");

			$fieldGroupsFile = $targetDir . DS . "system_tab_section" . DS . $selectedTab.".".$selectedSection.".php";
			$fieldGroups = [];
			if(file_exists($fieldGroupsFile)){
				$fieldGroups = include($fieldGroupsFile);
				$fieldGroups = SystemConfig::resetLoadedValue()->assignValue($fieldGroups, $selectedTab."/".$selectedSection);
			}

			return response()->json([
				'message' => 'System configuration successfully saved.',
				'data' => [
					"field_groups" => $fieldGroups,
				]
			], 200);
		} catch (\Throwable $th) {
			DB::rollback();
			return response()->json([
				'message' => $th->getMessage()
			], 406);
		}
	}
}
?>