<?php
/**
 * course modules tests
 *
 * @package    block_course_modules
 
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Online users testcase
 *
 * @package    block_course_modules
 * @category   test
 * @copyright  2015 University of Nottingham <www.nottingham.ac.uk>
 * @author     Barry Oosthuizen <barry.oosthuizen@nottingham.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_course_modules_testcase extends advanced_testcase {

     public function test_get_content() {
	
	global $USER,$DB,$CFG;

        $this->resetAfterTest();

		$courseid = '2';
		$modulename = 'testmodule';
		$userid = 2;		
		$activitycompletion=time();
		$moduleid=3;
	    $expectedviewlink=$CFG->wwwroot.'/mod/'.$modulename.'/view.php?id='.$moduleid;
		$activityname='test activityname';
		
		//Setup test 
		$course_moduledata=$DB->get_records('course_modules',array('course'=>$courseid,'visible'=>1));
        foreach($course_moduledata as $rec){
            $module=$DB->get_record('modules',array('id'=>$rec->module));
            $activity=$DB->get_record($module->name,array('id'=>$rec->instance));
            $viewlink=$CFG->wwwroot.'/mod/'.$module->name.'/view.php?id='.$rec->id;
            $activity_completion=$DB->get_record('course_modules_completion',array('coursemoduleid'=>$rec->id,'userid'=>$USER->id,'completionstate'=>1));
            
            if($activity_completion->timemodified>0){
            $completiondate=date('d-M-Y',$activity_completion->timemodified);
            }else{
            $completiondate='';
            }
			// Check the data are all as expected.
			$this->assertEquals($modulename, $module->name);
			$this->assertEquals($userid, $USER->id);
			$this->assertEquals($activityname, $activity->name);
			$this->assertEquals($expectedviewlink, $viewlink);
			$this->assertEquals($activitycompletion, $activity_completion->timemodified);
	}
        
}

}
