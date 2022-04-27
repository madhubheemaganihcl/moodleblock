<?php
/**
 * course_modules block caps.
 *
 * @package    block_course_modules
 * @author Madhu Bheemagani86 <madhu.bheemagani86@gmail.com>
 */


include_once($CFG->dirroot . '/course/lib.php');

class block_course_modules extends block_base {
    function init() {
        $this->title = get_string('pluginname', 'block_course_modules');
    }

    function has_config() {
        return false;
    }
    
     public function get_content() {
        global $PAGE, $USER,$DB,$CFG;
        
        $courseid     = optional_param('id', 0, PARAM_INT);
        
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->text   = '';
        
        if (empty($courseid)) {
            return $this->content;
        }
        
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

            $this->content->text.=html_writer::start_tag('div');
            $this->content->text.=html_writer::tag('a', $rec->id.' - '.$activity->name.' - '.$completiondate,array('href'=>$viewlink));
            $this->content->text.=html_writer::end_tag('div');

        }
        return $this->content;
    }


public function applicable_formats() {
  return array(
           'site-index' => false,
          'course-view' => true, 
   'course-view-social' => false,
                  'mod' => false,
             'mod-quiz' => false
  );
}
}