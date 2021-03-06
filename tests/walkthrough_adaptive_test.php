<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/questionlib.php');
require_once(__DIR__ . '/fixtures/test_base.php');

// Unit tests for the Stack question type.
//
// @copyright 2012 The Open University.
// @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.

/**
 * @group qtype_stack
 */
class qtype_stack_walkthrough_adaptive_test extends qtype_stack_walkthrough_test_base {

    public function test_test0_validate_then_submit_right_first_time() {

        // Create the stack question 'test0'.
        $q = test_question_maker::make_question('stack', 'test0');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->assertEquals('adaptivemultipart',
                $this->quba->get_question_attempt($this->slot)->get_behaviour_name());
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/What is/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Process a validate request.
        $this->process_submission(array('ans1' => '2', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '2');
        $this->check_output_contains_input_validation('ans1');
        // Since the answer is a number there are no variables.
        $this->check_output_does_not_contain_lang_string('studentValidation_listofvariables',
                'qtype_stack', '\( \left[ x \right]\)');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the correct answer.
        $this->process_submission(array('ans1' => '2', 'ans1_val' => '2', '-submit' => 1));

        // Verify.
        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(1);
        $this->check_prt_score('firsttree', 1, 0);
        $this->render();
        $this->check_output_contains_text_input('ans1', '2');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_test0_validate_then_submit_right_first_time_with_forceclean() {
        global $CFG;
        // Turn on the forceclean.
        $CFG->forceclean = true;

        // Create the stack question 'test0'.
        $q = test_question_maker::make_question('stack', 'test0');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->assertEquals('adaptivemultipart',
                $this->quba->get_question_attempt($this->slot)->get_behaviour_name());
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/What is/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Process a validate request.
        $this->process_submission(array('ans1' => '2', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '2');
        $this->check_output_contains_input_validation('ans1');
        // Since the answer is a number there are no variables.
        $this->check_output_does_not_contain_lang_string('studentValidation_listofvariables',
                'qtype_stack', '\( \left[ x \right]\)');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the correct answer.
        $this->process_submission(array('ans1' => '2', 'ans1_val' => '2', '-submit' => 1));

        // Verify.
        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(1);
        $this->check_prt_score('firsttree', 1, 0);
        $this->render();
        $this->check_output_contains_text_input('ans1', '2');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_test0_validate_then_submit_wrong_answer() {

        // Create the stack question 'test0'.
        $q = test_question_maker::make_question('stack', 'test0');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        $this->render();

        // Process a validate request.
        $this->process_submission(array('ans1' => '3', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);

        $this->render();
        $this->check_output_contains_text_input('ans1', '3');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the correct answer.
        $this->process_submission(array('ans1' => '3', 'ans1_val' => '3', '-submit' => 1));

        // Verify.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->check_prt_score('firsttree', 0, 0.3);
        $this->render();
        $this->check_output_contains_text_input('ans1', '3');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_test0_validate_then_submit_question() {

        // Create the stack question 'test0'.
        $q = test_question_maker::make_question('stack', 'test0');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        $this->render();

        // Process a validate request containing a variable.
        $this->process_submission(array('ans1' => 'sin(x)', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'sin(x)');
        $expectedvarlist = get_string('studentValidation_listofvariables',
                'qtype_stack', '\( \left[ x \right]\)');
        $this->assertContentWithMathsContains($expectedvarlist, $this->currentoutput);
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a validate request.
        $this->process_submission(array('ans1' => '1+1', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '1+1');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the correct answer.
        $this->process_submission(array('ans1' => '1+1', 'ans1_val' => '1+1', '-submit' => 1));

        // Verify.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->check_prt_score('firsttree', 0, 0.3);
        $this->render();
        $this->check_output_contains_text_input('ans1', '1+1');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_test1_validate_then_submit_right_first_time() {

        // Create the stack question 'test1'.
        $q = test_question_maker::make_question('stack', 'test1');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->assertEquals('adaptivemultipart',
                $this->quba->get_question_attempt($this->slot)->get_behaviour_name());
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/Find/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Process a validate request.
        // Notice here we get away with including single letter question variables in the answer.
        $this->process_submission(array('ans1' => '(v-a)^(n+1)/(n+1)+c', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '(v-a)^(n+1)/(n+1)+c');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the correct answer.
        $this->process_submission(array('ans1' => '(v-a)^(n+1)/(n+1)+c', 'ans1_val' => '(v-a)^(n+1)/(n+1)+c', '-submit' => 1));

        // Verify.
        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(1);
        $this->check_prt_score('PotResTree_1', 1, 0);
        $this->render();
        $this->check_output_contains_text_input('ans1', '(v-a)^(n+1)/(n+1)+c');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('PotResTree_1');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_test1_validate_wrong_validate_right_submit_right() {

        $q = test_question_maker::make_question('stack', 'test1');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/Find/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Process a validate request.
        $this->process_submission(array('ans1' => '(v-a)^(n+1)/(n+1)', '-submit' => 1));

        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '(v-a)^(n+1)/(n+1)');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit, but with a changed answer.
        $this->process_submission(array('ans1' => '(v-a)^(n+1)/(n+1)+c', 'ans1_val' => '(v-a)^(n+1)/(n+1)', '-submit' => 1));

        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '(v-a)^(n+1)/(n+1)+c');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit with the correct answer.
        $this->process_submission(array('ans1' => '(v-a)^(n+1)/(n+1)+c', 'ans1_val' => '(v-a)^(n+1)/(n+1)+c', '-submit' => 1));

        // Verify.
        $this->check_current_mark(1);
        $this->check_prt_score('PotResTree_1', 1, 0);
        $this->render();
        $this->check_output_contains_text_input('ans1', '(v-a)^(n+1)/(n+1)+c');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('PotResTree_1');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_test1_invalid_valid_but_wrong_with_specific_feedback() {

        // Create a stack question.
        $q = test_question_maker::make_question('stack', 'test1');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/Find/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Process a validate request.
        // Invalid answer.
        $this->process_submission(array('ans1' => 'n*(v-a)^(n-1', '-submit' => 1));

        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'n*(v-a)^(n-1');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
            new question_pattern_expectation('/missing right/')
        );

        // Valid answer.
        $this->process_submission(array('ans1' => 'n*(v-a)^(n-1)', '-submit' => 1));

        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'n*(v-a)^(n-1)');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Submit known mistake - look for specific feedback.
        $this->process_submission(array('ans1' => 'n*(v-a)^(n-1)', 'ans1_val' => 'n*(v-a)^(n-1)', '-submit' => 1));

        $this->check_current_mark(0);
        $this->check_prt_score('PotResTree_1', 0, 0.25);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'n*(v-a)^(n-1)');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('PotResTree_1');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
            new question_pattern_expectation('/differentiated instead!/')
        );
    }

    public function test_test1_invalid_student_uses_question_variables() {

        // Create a stack question.
        $q = test_question_maker::make_question('stack', 'test1');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/Find/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Process a validate request.
        // Invalid answer.
        $this->process_submission(array('ans1' => 'ta', '-submit' => 1));

        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'ta');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
            new question_pattern_expectation('/is forbidden/')
        );

        $this->process_submission(array('ans1' => 'ta1', '-submit' => 1));

        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'ta1');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
            new question_pattern_expectation('/is forbidden/')
        );
    }

    public function test_test1_invalid_student_uses_forbidden_words() {

        // Create a stack question.
        $q = test_question_maker::make_question('stack', 'test1');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/Find/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Process a validate request.
        // Invalid answer.
        $this->process_submission(array('ans1' => 'int((x-6)^4,x)', '-submit' => 1));

        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'int((x-6)^4,x)');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
            new question_pattern_expectation('/is forbidden/')
        );

    }

    public function test_test1_invalid_student_uses_forbidden_words_fromlist() {
        // Create a stack question.
        $q = test_question_maker::make_question('stack', 'test1');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/Find/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Process a validate request.
        // Invalid answer.
        $this->process_submission(array('ans1' => 'solve((x-6)^4,x)', '-submit' => 1));

        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'solve((x-6)^4,x)');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/is forbidden/')
        );

    }

    public function test_test1_valid_student_uses_allowed_words() {
        // Create a stack question.
        $q = test_question_maker::make_question('stack', 'test1');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/Find/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Process a validate request, with a function name from the allowwords list.
        $this->process_submission(array('ans1' => 'popup(x^2+c)', '-submit' => 1));

        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'popup(x^2+c)');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the correct answer.
        $this->process_submission(array('ans1' => 'popup(x^2+c)', 'ans1_val' => 'popup(x^2+c)', '-submit' => 1));

        // Verify.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->check_prt_score('PotResTree_1', 0, 0.25);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'popup(x^2+c)');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('PotResTree_1');
        $this->check_output_does_not_contain_stray_placeholders();

    }

    public function test_test1_valid_student_uses_allowed_words_casesensitivity() {
        // Normally "Sin(x)" is invalid and will give the feedback from 'stackCas_unknownFunctionCase'.
        // In this question we have included 'Sin' in the inputs allowed words.
        // Create a stack question.
        $q = test_question_maker::make_question('stack', 'test1');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/Find/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Process a validate request, with a function name not from the allowwords list.
        $this->process_submission(array('ans1' => 'Cos(x^2+c)', '-submit' => 1));

        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'Cos(x^2+c)');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a validate request, with a function name from the allowwords list.
        $this->process_submission(array('ans1' => 'Sin(x^2+c)', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'Sin(x^2+c)');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the correct answer.
        $this->process_submission(array('ans1' => 'Sin(x^2+c)', 'ans1_val' => 'Sin(x^2+c)', '-submit' => 1));

        // Verify.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->check_prt_score('PotResTree_1', 0, 0.25);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'Sin(x^2+c)');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('PotResTree_1');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_test3_repeat_wrong_response_only_penalised_once() {
        // The scenario is this: (we use only the ans3 part of test3, leaving the others blank.)
        //
        // Resp.  State Try Raw mark Mark Penalty
        // 1. x   valid -   -        -    -
        // 2. x   score X   0.5      0.5  0.1
        // 3. x   score -   -        -    -
        // 4. x+1 valid -   -        -    -
        // 5. x+1 score X   0        0.5  0.1
        // 6. x   valid -   -        -    -
        // 7. x   score X   0.5      0.5  0.0
        // 8. 0   valid -   -        -    -
        // 9. 0   score X   1        0.8  0.0
        //
        // When reading this test, note that check_prt_score checks the score
        // returned by get_prt_result, which is the low-level routine that
        // calculates the score for the PRT given the response. The
        // ...->get_behaviour_var('_tries_oddeven') bit checks to see if the
        // behaviour acutally counted this response as a try.

        // Create a stack question.
        $q = test_question_maker::make_question('stack', 'test3_penalty0_1');

        $this->start_attempt_at_question($q, 'adaptive', 4);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('oddeven', null, null);
        $this->assertNull($this->quba->get_question_attempt($this->slot)->get_last_step()->get_behaviour_var('_tries_oddeven'));
        $this->render();
        $this->check_output_contains_text_input('ans3');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_does_not_contain_feedback_expectation()
        );

        // Validate ans3 => 'x'.
        $this->process_submission(array('ans3' => 'x', '-submit' => 1));

        $this->check_current_mark(null);
        $this->check_prt_score('oddeven', null, null);
        $this->assertNull($this->quba->get_question_attempt($this->slot)->get_last_step()->get_behaviour_var('_tries_oddeven'));
        $this->render();
        $this->check_output_contains_text_input('ans3', 'x');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Score ans3 => 'x'.
        $this->process_submission(array('ans3' => 'x', 'ans3_val' => 'x', '-submit' => 1));

        $this->check_current_mark(0.5);
        $this->check_prt_score('oddeven', 0.5, 0.1);
        $this->assertEquals(1, $this->quba->get_question_attempt(
                $this->slot)->get_last_step()->get_behaviour_var('_tries_oddeven'));
        $this->render();
        $this->check_output_contains_text_input('ans3', 'x');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->assertContentWithMathsContains('Your answer is not an even function. Look,'
                .' \[ f(x)-f(-x)={2\\cdot x} \neq 0.\]', $this->currentoutput);

        // Score ans3 => 'x'. (put it an ans1 to validate, to force the creation of a new step.)
        $this->process_submission(array('ans3' => 'x', 'ans3_val' => 'x', 'ans1' => 'x', '-submit' => 1));

        $this->check_current_mark(0.5);
        $this->check_prt_score('oddeven', 0.5, 0.1);
        $this->assertNull($this->quba->get_question_attempt(
                $this->slot)->get_last_step()->get_behaviour_var('_tries_oddeven'));
        $this->render();
        $this->check_output_contains_text_input('ans3', 'x');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_does_not_contain_stray_placeholders();

        // Validate ans3 => 'x + 1'.
        $this->process_submission(array('ans3' => 'x + 1', 'ans3_val' => 'x', '-submit' => 1));

        $this->check_current_mark(0.5);
        $this->check_prt_score('oddeven', null, null);
        $this->assertNull($this->quba->get_question_attempt(
                $this->slot)->get_last_step()->get_behaviour_var('_tries_oddeven'));
        $this->render();
        $this->check_output_contains_text_input('ans3', 'x + 1');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Score ans3 => 'x + 1'.
        $this->process_submission(array('ans3' => 'x + 1', 'ans3_val' => 'x + 1', '-submit' => 1));

        $this->check_current_mark(0.5);
        $this->check_prt_score('oddeven', 0, 0.1);
        $this->assertEquals(2, $this->quba->get_question_attempt(
                $this->slot)->get_last_step()->get_behaviour_var('_tries_oddeven'));
        $this->render();
        $this->check_output_contains_text_input('ans3', 'x + 1');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_does_not_contain_stray_placeholders();

        // Validate ans3 => 'x'.
        $this->process_submission(array('ans3' => 'x', 'ans3_val' => 'x + 1', '-submit' => 1));

        $this->check_current_mark(0.5);
        $this->check_prt_score('oddeven', null, null);
        $this->assertNull($this->quba->get_question_attempt(
                $this->slot)->get_last_step()->get_behaviour_var('_tries_oddeven'));
        $this->render();
        $this->check_output_contains_text_input('ans3', 'x');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Score ans3 => 'x'.
        $this->process_submission(array('ans3' => 'x', 'ans3_val' => 'x', '-submit' => 1));

        $this->check_current_mark(0.5);
        $this->check_prt_score('oddeven', 0.5, 0.1);
        $this->assertEquals(3, $this->quba->get_question_attempt(
                $this->slot)->get_last_step()->get_behaviour_var('_tries_oddeven'));
        $this->render();
        $this->check_output_contains_text_input('ans3', 'x');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_does_not_contain_stray_placeholders();

        // Validate ans3 => '0'.
        $this->process_submission(array('ans3' => '0', 'ans3_val' => 'x', '-submit' => 1));

        $this->check_current_mark(0.5);
        $this->check_prt_score('oddeven', null, null);
        $this->assertNull($this->quba->get_question_attempt(
                $this->slot)->get_last_step()->get_behaviour_var('_tries_oddeven'));
        $this->render();
        $this->check_output_contains_text_input('ans3', '0');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Score ans3 => '0'.
        $this->process_submission(array('ans3' => '0', 'ans3_val' => '0', '-submit' => 1));

        $this->check_current_mark(0.8);
        $this->check_prt_score('oddeven', 1, 0);
        $this->assertEquals(4, $this->quba->get_question_attempt(
                $this->slot)->get_last_step()->get_behaviour_var('_tries_oddeven'));
        $this->render();
        $this->check_output_contains_text_input('ans3', '0');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_test3_submit_and_finish_before_validating() {
        // Create a stack question.
        $q = test_question_maker::make_question('stack', 'test3');
        $this->start_attempt_at_question($q, 'adaptive', 4);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('odd', null, null);
        $this->check_prt_score('even', null, null);
        $this->check_prt_score('oddeven', null, null);
        $this->check_prt_score('unique', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_contains_text_input('ans2');
        $this->check_output_contains_text_input('ans3');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), '', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );
        $this->assertNull($this->quba->get_response_summary($this->slot));

        // Save a partially correct, partially complete response.
        $this->process_submission(array('ans1' => 'x^3', 'ans2' => 'x^2', 'ans3' => 'x', 'ans4' => ''));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('odd', null, null);
        $this->check_prt_score('even', null, null);
        $this->check_prt_score('oddeven', null, null);
        $this->check_prt_score('unique', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3');
        $this->check_output_contains_text_input('ans2', 'x^2');
        $this->check_output_contains_text_input('ans3', 'x');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'false', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );
        $this->assertNull($this->quba->get_response_summary($this->slot));

        // Submit all and finish before validating.
        $this->quba->finish_all_questions();

        $this->check_current_state(question_state::$gradedpartial);
        $this->check_current_mark(2.5);
        $this->check_prt_score('odd', 1, 0, true);
        $this->check_prt_score('even', 1, 0, true);
        $this->check_prt_score('oddeven', 0.5, 0.4, true);
        $this->check_prt_score('unique', null, null, true);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3', false);
        $this->check_output_contains_text_input('ans2', 'x^2', false);
        $this->check_output_contains_text_input('ans3', 'x', false);
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_contains_prt_feedback('odd');
        $this->check_output_contains_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_does_not_contain_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), '', false),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );
        $this->assertEquals('ans1: x^3 [valid]; ans2: x^2 [valid]; ans3: x [valid]; odd: #; even: #; oddeven: #; unique: #',
                $this->quba->get_response_summary($this->slot));
    }

    public function test_test3_submit_wrong_response_correct_then_stubmit() {
        // Create a stack question.
        $q = test_question_maker::make_question('stack', 'test3');
        $this->start_attempt_at_question($q, 'adaptive', 4);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_contains_text_input('ans2');
        $this->check_output_contains_text_input('ans3');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
        $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), '', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Try to submit a response:
        // 1. all parts wrong but valid.
        $this->process_submission(array('ans1' => 'x^2', 'ans2' => 'x^3', 'ans3' => '1+x^3', 'ans4' => 'false', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^2');
        $this->check_output_contains_text_input('ans2', 'x^3');
        $this->check_output_contains_text_input('ans3', '1+x^3');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_prt_feedback('odd');
        $this->check_output_does_not_contain_prt_feedback('even');
        $this->check_output_does_not_contain_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'false', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Submit again without editing.
        $this->process_submission(array('ans1' => 'x^2', 'ans2' => 'x^3', 'ans3' => '1+x^3', 'ans4' => 'false',
                'ans1_val' => 'x^2', 'ans2_val' => 'x^3', 'ans3_val' => '1+x^3', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->render();
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_contains_prt_feedback('odd');
        $this->check_output_contains_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'false', true),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );
        $this->check_current_output(
                new question_pattern_expectation('/Incorrect answer./')
        );
        $this->check_current_output(
                new question_no_pattern_expectation('/Your answer is partially correct./')
        );

    }

    public function test_test3_save_invalid_response_correct_then_stubmit() {
        // Create a stack question.
        $q = test_question_maker::make_question('stack', 'test3');
        $this->start_attempt_at_question($q, 'adaptive', 4);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_contains_text_input('ans2');
        $this->check_output_contains_text_input('ans3');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), '', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Try to submit a response:
        // 1. right, not yet validated
        // 2. invalid
        // 3. right, not yet validated
        // 4. right, validation not required.
        $this->process_submission(array('ans1' => 'x^3', 'ans2' => '(x +', 'ans3' => '0', 'ans4' => 'true', '-submit' => 1));

        $this->check_current_state(question_state::$invalid);
        $this->check_current_mark(1.0);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3');
        $this->check_output_contains_text_input('ans2', '(x +');
        $this->check_output_contains_text_input('ans3', '0');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_does_not_contain_prt_feedback('odd');
        $this->check_output_does_not_contain_prt_feedback('even');
        $this->check_output_does_not_contain_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'false', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Submit again without editing. 1. and 3. bits should now be graded.
        $this->process_submission(array('ans1' => 'x^3', 'ans2' => '(x +', 'ans3' => '0', 'ans4' => 'true',
                                        'ans1_val' => 'x^3', 'ans3_val' => '0', '-submit' => 1));

        $this->check_current_state(question_state::$invalid);
        $this->check_current_mark(3.0);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3');
        $this->check_output_contains_text_input('ans2', '(x +');
        $this->check_output_contains_text_input('ans3', '0');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_contains_prt_feedback('odd');
        $this->check_output_does_not_contain_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'false', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Now fix the response to 2. and submit. Previously invalid bit should only be validated, not graded yet.
        $this->process_submission(array('ans1' => 'x^3', 'ans2' => 'x^2', 'ans3' => '0', 'ans4' => 'true',
                                        'ans1_val' => 'x^3', 'ans3_val' => '0', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(3.0);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3');
        $this->check_output_contains_text_input('ans2', 'x^2');
        $this->check_output_contains_text_input('ans3', '0');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_contains_prt_feedback('odd');
        $this->check_output_does_not_contain_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'false', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Submit again. Should now all be graded (and right).
        $this->process_submission(array('ans1' => 'x^3', 'ans2' => 'x^2', 'ans3' => '0', 'ans4' => 'true',
                                        'ans1_val' => 'x^3', 'ans2_val' => 'x^2', 'ans3_val' => '0', '-submit' => 1));

        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(4.0);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3');
        $this->check_output_contains_text_input('ans2', 'x^2');
        $this->check_output_contains_text_input('ans3', '0');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_contains_prt_feedback('odd');
        $this->check_output_contains_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'true', true),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Submit all and finish - should update state from complete to gradedright.
        $this->quba->finish_all_questions();

        $this->check_current_state(question_state::$gradedright);
        $this->check_current_mark(4.0);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3', false);
        $this->check_output_contains_text_input('ans2', 'x^2', false);
        $this->check_output_contains_text_input('ans3', '0', false);
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_contains_prt_feedback('odd');
        $this->check_output_contains_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'true', false),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );
    }

    public function test_test3_complex_scenario() {
        // @codingStandardsIgnoreStart
        /**
         * Here are the sequence of responses we are going to test. When
         * a particular PRT generates a grades, that is shown in brackets as
         * raw fraction - penalty.
         *
         *     odd         even        oddeven       unique     Mark so far
         *  1. x^3         -           x             -          -
         *  2. x^3 (1)     -           x   (0.5)     -          1.5
         *  3. (x          (x          x+1           F (0)      1.5
         *  4. x)          x^2         x+1 (0-0.1)   -          1.5
         *  5. x^2         x           x^5           -          1.5
         *  6. x^2 (0-0)   x^2         x^5 (0.5-0.2) T (1-0.1)  2.4
         *  7. x           x^2 (1)     x+3           T (1-0.1)  3.4
         *  8. x   (1-0.1) -           x+3 (0-0.3)   T (1-0.1)  3.4
         *  9. x^3         x^2         0             T (1-0.1)  3.4
         * 10. x^3 (1-0.1) x^2 (1-0.0) 0   (1-0.4)   T (1-0.1)  3.5
         *
         * Best mark
         *     1.0         1.0         0.6           0.9        3.5
         *
         * Hopefully this summary makes the following easier to understand.
         */
        // @codingStandardsIgnoreEnd

        // Create a stack question.

        $q = test_question_maker::make_question('stack', 'test3_penalty0_1');
        $this->start_attempt_at_question($q, 'adaptive', 4);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_contains_text_input('ans2');
        $this->check_output_contains_text_input('ans3');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), '', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );
        $this->assertNull($this->quba->get_response_summary($this->slot));

        // Step 1.
        $this->process_submission(array('ans1' => 'x^3', 'ans2' => '', 'ans3' => 'x', 'ans4' => '', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3');
        $this->check_output_contains_text_input('ans2', '');
        $this->check_output_contains_text_input('ans3', 'x');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), '', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );
        $this->assertEquals('ans1: x^3 [valid]; ans3: x [valid]; odd: #; even: #; oddeven: #; unique: #',
                $this->quba->get_response_summary($this->slot));

        // Step 2.
        $this->process_submission(array('ans1' => 'x^3', 'ans2' => '', 'ans3' => 'x', 'ans4' => '',
            'ans1_val' => 'x^3', 'ans3_val' => 'x', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(1.5);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3');
        $this->check_output_contains_text_input('ans2', '');
        $this->check_output_contains_text_input('ans3', 'x');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_contains_prt_feedback('odd');
        $this->check_output_does_not_contain_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_does_not_contain_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), '', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );
        $this->assertEquals('ans1: x^3 [score]; ans3: x [score]; '
                . 'odd: odd-0-1; even: #; oddeven: oddeven-0-1 | oddeven-1-0; unique: #',
                $this->quba->get_response_summary($this->slot));

        // Step 3.
        $this->process_submission(array('ans1' => '(x', 'ans2' => '(x', 'ans3' => 'x+1', 'ans4' => 'false',
            'ans1_val' => 'x^3', 'ans3_val' => 'x', '-submit' => 1));

        $this->check_current_state(question_state::$invalid);
        $this->check_current_mark(1.5);
        $this->render();
        $this->check_output_contains_text_input('ans1', '(x');
        $this->check_output_contains_text_input('ans2', '(x');
        $this->check_output_contains_text_input('ans3', 'x+1');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_does_not_contain_prt_feedback('odd');
        $this->check_output_does_not_contain_prt_feedback('even');
        $this->check_output_does_not_contain_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'false', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );
        $this->assertEquals('ans1: (x [invalid]; ans2: (x [invalid]; ans3: x+1 [valid]; ans4: false [score]; '
                . 'odd: #; even: #; oddeven: #; unique: unique-0-0',
                $this->quba->get_response_summary($this->slot));

        // Step 4.
        $this->process_submission(array('ans1' => 'x)', 'ans2' => 'x^2', 'ans3' => 'x+1', 'ans4' => '',
            'ans3_val' => 'x+1', '-submit' => 1));

        $this->check_current_state(question_state::$invalid);
        $this->check_current_mark(1.5);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x)');
        $this->check_output_contains_text_input('ans2', 'x^2');
        $this->check_output_contains_text_input('ans3', 'x+1');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_does_not_contain_prt_feedback('odd');
        $this->check_output_does_not_contain_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_does_not_contain_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), '', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );
        $this->assertEquals('ans1: x) [invalid]; ans2: x^2 [valid]; ans3: x+1 [score]; '
                .'odd: #; even: #; oddeven: oddeven-0-0 | oddeven-1-0; unique: #',
                $this->quba->get_response_summary($this->slot));

        // Step 5.
        $this->process_submission(array('ans1' => 'x^2', 'ans2' => 'x', 'ans3' => 'x^5', 'ans4' => '',
            'ans2_val' => 'x^2', 'ans3_val' => 'x+1', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(1.5);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^2');
        $this->check_output_contains_text_input('ans2', 'x');
        $this->check_output_contains_text_input('ans3', 'x^5');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_does_not_contain_prt_feedback('odd');
        $this->check_output_does_not_contain_prt_feedback('even');
        $this->check_output_does_not_contain_prt_feedback('oddeven');
        $this->check_output_does_not_contain_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), '', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );
        $this->assertEquals('ans1: x^2 [valid]; ans2: x [valid]; ans3: x^5 [valid]; '
                . 'odd: #; even: #; oddeven: #; unique: #',
                $this->quba->get_response_summary($this->slot));

        // Step 6.
        $this->process_submission(array('ans1' => 'x^2', 'ans2' => 'x^2', 'ans3' => 'x^5', 'ans4' => 'true',
            'ans1_val' => 'x^2', 'ans2_val' => 'x', 'ans3_val' => 'x^5', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(2.4);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^2');
        $this->check_output_contains_text_input('ans2', 'x^2');
        $this->check_output_contains_text_input('ans3', 'x^5');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_contains_prt_feedback('odd');
        $this->check_output_does_not_contain_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'true', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );
        $this->assertEquals('ans1: x^2 [score]; ans2: x^2 [valid]; ans3: x^5 [score]; ans4: true [score]; '
                . 'odd: odd-0-0; even: #; oddeven: oddeven-0-1 | oddeven-1-0; unique: ATLogic_True | unique-0-1',
                $this->quba->get_response_summary($this->slot));

        // Step 7.
        $this->process_submission(array('ans1' => 'x', 'ans2' => 'x^2', 'ans3' => 'x+3', 'ans4' => 'true',
            'ans1_val' => 'x^2', 'ans2_val' => 'x^2', 'ans3_val' => 'x^5', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(3.4);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x');
        $this->check_output_contains_text_input('ans2', 'x^2');
        $this->check_output_contains_text_input('ans3', 'x+3');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_does_not_contain_prt_feedback('odd');
        $this->check_output_contains_prt_feedback('even');
        $this->check_output_does_not_contain_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'true', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );
        $this->assertEquals('ans1: x [valid]; ans2: x^2 [score]; ans3: x+3 [valid]; ans4: true [score]; '
                . 'odd: #; even: even-0-1; oddeven: #; unique: ATLogic_True | unique-0-1',
                $this->quba->get_response_summary($this->slot));

        // Step 8.
        $this->process_submission(array('ans1' => 'x', 'ans2' => '', 'ans3' => 'x+3', 'ans4' => 'true',
            'ans1_val' => 'x', 'ans2_val' => 'x^2', 'ans3_val' => 'x+3', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(3.4);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x');
        $this->check_output_contains_text_input('ans2', '');
        $this->check_output_contains_text_input('ans3', 'x+3');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_contains_prt_feedback('odd');
        $this->check_output_does_not_contain_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'true', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );
        $this->assertEquals('ans1: x [score]; ans3: x+3 [score]; ans4: true [score]; '
                . 'odd: odd-0-1; even: #; oddeven: oddeven-0-0 | oddeven-1-0; unique: ATLogic_True | unique-0-1',
                $this->quba->get_response_summary($this->slot));

        // Step 9.
        $this->process_submission(array('ans1' => 'x^3', 'ans2' => 'x^2', 'ans3' => '0', 'ans4' => 'true',
            'ans1_val' => 'x', 'ans3_val' => 'x+3', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(3.4);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3');
        $this->check_output_contains_text_input('ans2', 'x^2');
        $this->check_output_contains_text_input('ans3', '0');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_does_not_contain_prt_feedback('odd');
        $this->check_output_does_not_contain_prt_feedback('even');
        $this->check_output_does_not_contain_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'true', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );
        $this->assertEquals('ans1: x^3 [valid]; ans2: x^2 [valid]; ans3: 0 [valid]; ans4: true [score]; '
                . 'odd: #; even: #; oddeven: #; unique: ATLogic_True | unique-0-1',
                $this->quba->get_response_summary($this->slot));

        // Step 10.
        $this->process_submission(array('ans1' => 'x^3', 'ans2' => 'x^2', 'ans3' => '0', 'ans4' => 'true',
            'ans1_val' => 'x^3', 'ans2_val' => 'x^2', 'ans3_val' => '0', '-submit' => 1));

        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(3.5);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3');
        $this->check_output_contains_text_input('ans2', 'x^2');
        $this->check_output_contains_text_input('ans3', '0');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_contains_prt_feedback('odd');
        $this->check_output_contains_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'true', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );
        $this->assertEquals('ans1: x^3 [score]; ans2: x^2 [score]; ans3: 0 [score]; ans4: true [score]; '
                . 'odd: odd-0-1; even: even-0-1; oddeven: oddeven-0-1 | oddeven-1-1; unique: ATLogic_True | unique-0-1',
                $this->quba->get_response_summary($this->slot));

        // Submit all and finish - should update state from complete to gradedright.
        $this->quba->finish_all_questions();

        $this->check_current_state(question_state::$gradedright);
        $this->check_current_mark(3.5);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3', false);
        $this->check_output_contains_text_input('ans2', 'x^2', false);
        $this->check_output_contains_text_input('ans3', '0', false);
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_contains_prt_feedback('odd');
        $this->check_output_contains_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'true', false),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );
        $this->assertEquals('ans1: x^3 [score]; ans2: x^2 [score]; ans3: 0 [score]; ans4: true [score]; '
                . 'odd: odd-0-1; even: even-0-1; oddeven: oddeven-0-1 | oddeven-1-1; unique: ATLogic_True | unique-0-1',
                $this->quba->get_response_summary($this->slot));
    }

    public function test_divide_by_0() {
        // Create a stack question.
        $q = test_question_maker::make_question('stack', 'divide');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('prt1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Validate the response 0.
        $this->process_submission(array('ans1' => '0', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('prt1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '0');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Now submit the response 0. Causes a divide by 0.
        $this->process_submission(array('ans1' => '0', 'ans1_val' => '0', '-submit' => 1));

        $this->check_current_state(question_state::$invalid);
        $this->check_current_mark(null);
        $this->check_prt_score('prt1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '0');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('prt1');
        $this->check_output_does_not_contain_stray_placeholders();
        // The error message on the next line in intentionally truncated, because
        // different versions of Maxima report this as 'Division by zero.' or
        // 'Division by 0'. Fortunately this check is good enough.
        $this->check_output_contains_lang_string('TEST_FAILED', 'qtype_stack', array('errors' => 'Division by '));

        // Validate the response 1/2 (correct).
        $this->process_submission(array('ans1' => '1/2', 'ans1_val' => '0', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('prt1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '1/2');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Now submit the response 1/2.
        $this->process_submission(array('ans1' => '1/2', 'ans1_val' => '1/2', '-submit' => 1));

        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(1); // No penalties applied.
        $this->check_prt_score('prt1', 1, 0);
        $this->render();
        $this->check_output_contains_text_input('ans1', '1/2');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('prt1');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_output_does_not_contain_lang_string('TEST_FAILED', 'qtype_stack', array('errors' => 'Division by zero.'));
    }

    public function test_numsigfigs_validate_then_submit_right_first_time() {

        // Create the stack question 'test0'.
        $q = test_question_maker::make_question('stack', 'numsigfigs');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->assertEquals('adaptivemultipart',
                $this->quba->get_question_attempt($this->slot)->get_behaviour_name());
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/Please round/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Process a validate request.
        $this->process_submission(array('ans1' => '3.14', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '3.14');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the correct answer.
        $this->process_submission(array('ans1' => '3.14', 'ans1_val' => '3.14', '-submit' => 1));

        // Verify.
        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(1);
        $this->check_prt_score('firsttree', 1, 0);
        $this->render();
        $this->check_output_contains_text_input('ans1', '3.14');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_numsigfigs_trailing_zero() {

        // Create the stack question 'test0'.
        $q = test_question_maker::make_question('stack', 'numsigfigszeros');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->assertEquals('adaptivemultipart',
                $this->quba->get_question_attempt($this->slot)->get_behaviour_name());
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/Please type in/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
                );

        // Process a validate request.
        $this->process_submission(array('ans1' => '0.04', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '0.04');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the answer without a trailing zero.
        $this->process_submission(array('ans1' => '0.04', 'ans1_val' => '0.04', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->check_prt_score('firsttree', 0, 0.2);
        $this->render();
        $this->check_output_contains_text_input('ans1', '0.04');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a validation of the correct answer.
        $this->process_submission(array('ans1' => '0.040', 'ans1_val' => '0.04', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->render();
        $this->check_output_contains_text_input('ans1', '0.040');
        $this->check_output_contains_input_validation('ans1');

        // The line below currently fails.  It should not.
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the correct answer.
        $this->process_submission(array('ans1' => '0.040', 'ans1_val' => '0.040', '-submit' => 1));

        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(0.8);
        $this->check_prt_score('firsttree', 1, 0);
        $this->render();
        $this->check_output_contains_text_input('ans1', '0.040');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_test0_save_does_validate_but_does_not_submit() {

        // Create the stack question 'test0'.
        $q = test_question_maker::make_question('stack', 'test0');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->assertEquals('adaptivemultipart',
                $this->quba->get_question_attempt($this->slot)->get_behaviour_name());
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/What is/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Just save a response. This will validate.
        $this->process_submission(array('ans1' => '2'));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '2');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Just save again. Nothing visible should change.
        $lastoutput = $this->currentoutput;
        $this->process_submission(array('ans1' => '2', 'ans1_val' => '2'));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->render();
        $this->assertEquals(str_replace('sequencecheck" value="2"', 'sequencecheck" value="3"', $lastoutput),
                    $this->currentoutput);
    }

    public function test_test_boolean_validate_then_submit_right_first_time() {

        // Create the stack question 'test_boolean'.
        $q = test_question_maker::make_question('stack', 'test_boolean');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->assertEquals('adaptivemultipart',
                $this->quba->get_question_attempt($this->slot)->get_behaviour_name());
        $this->render();
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
        new question_pattern_expectation('/What is/'),
            $this->get_does_not_contain_feedback_expectation(),
            $this->get_does_not_contain_num_parts_correct(),
            $this->get_no_hint_visible_expectation()
        );

        // Process an incorrect answer.
        $this->process_submission(array('ans1' => 'false', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_prt_score('firsttree', 0, 0.3);
        $this->check_current_mark(0);
        $this->render();
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the correct answer.
        $this->process_submission(array('ans1' => 'true', 'ans1_val' => 'false', '-submit' => 1));

        // Verify.
        $this->check_current_state(question_state::$complete);
        $this->check_prt_score('firsttree', 1, 0);
        $this->check_current_mark(0.7);
        $this->render();
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_1input2prts_specific_feedback_handling() {
        // Create a stack question.
        $q = test_question_maker::make_question('stack', '1input2prts');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        // Check the right behaviour is used.
        $this->assertEquals('adaptivemultipart', $this->quba->get_question_attempt($this->slot)->get_behaviour_name());

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Submit the correct response.
        $this->process_submission(array('ans1' => '12', 'ans1_val' => '12', '-submit' => 1));

        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(1);
        $this->render();
        $this->check_output_contains_text_input('ans1', '12');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('prt1');
        $this->check_output_contains_prt_feedback('prt2');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->assertRegExp('~' . preg_quote($q->prtcorrect, '~') . '~', $this->currentoutput);
        $this->check_current_output(
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );
    }

    public function test_test0_adaptive_nopenalties_wrong_then_right_then_regrade() {

        // Create the stack question 'test0'.
        $q = test_question_maker::make_question('stack', 'test0');
        $this->start_attempt_at_question($q, 'adaptivenopenalty', 1);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->assertEquals('adaptivemultipart',
                $this->quba->get_question_attempt($this->slot)->get_behaviour_name());
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/What is/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Submit a wrong answer with valdiation.
        $this->process_submission(array('ans1' => '1', 'ans1_val' => '1', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->check_prt_score('firsttree', 0, 0.3);
        $this->render();
        $this->check_output_contains_text_input('ans1', '1');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();

        // Submit the right answer with validation.
        $this->process_submission(array('ans1' => '2', 'ans1_val' => '2', '-submit' => 1));

        // Verify.
        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(1);
        $this->check_prt_score('firsttree', 1, 0);
        $this->render();
        $this->check_output_contains_text_input('ans1', '2');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();

        // Now regrade.
        $this->quba->regrade_all_questions();

        // Verify.
        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(1);
        $this->check_prt_score('firsttree', 1, 0);
        $this->render();
        $this->check_output_contains_text_input('ans1', '2');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();
    }


    public function test_single_char_vars() {

        // Create the stack question 'test-single-char_vars'.
        $q = test_question_maker::make_question('stack', 'single_char_vars');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        $this->render();

        // Process a validate request.
        $this->process_submission(array('ans1' => 'sin(x)', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'sin(x)');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the incorrect answer.
        $this->process_submission(array('ans1' => 'sin(x)', 'ans1_val' => 'sin(x)', '-submit' => 1));

        // Verify.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->check_prt_score('firsttree', 0, 0.3);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'sin(x)');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the correct answer and validate.
        $this->process_submission(array('ans1' => 'sin(xy)', '-submit' => 1));

        // Verify.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'sin(xy)');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the correct answer.
        $this->process_submission(array('ans1' => 'sin(xy)', 'ans1_val' => 'sin(xy)', '-submit' => 1));

        // Verify.
        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(0.7);
        $this->check_prt_score('firsttree', 1, 0);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'sin(xy)');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_guard_clause_prt_ok() {

        $q = test_question_maker::make_question('stack', 'runtime_prt_err');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        $this->render();

        // Process a validate request.
        $this->process_submission(array('ans1' => '[3*x+1+5]', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('Result', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '[3*x+1+5]');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the incorrect answer.
        $this->process_submission(array('ans1' => '[3*x+1+5]', 'ans1_val' => '[3*x+1+5]', '-submit' => 1));

        // Verify.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->check_prt_score('Result', 0, 0);
        $this->render();
        $this->check_output_contains_text_input('ans1', '[3*x+1+5]');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('Result');
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the correct answer and validate.
        $this->process_submission(array('ans1' => '[3*x+1=5]', '-submit' => 1));

        // Verify.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->check_prt_score('Result', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '[3*x+1=5]');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the correct answer.
        $this->process_submission(array('ans1' => '[3*x+1=5]', 'ans1_val' => '[3*x+1=5]', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->check_prt_score('Result', 0, 0.1);
        $this->render();
        $this->check_output_contains_text_input('ans1', '[3*x+1=5]');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('Result');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_guard_clause_prt_err() {

        $q = test_question_maker::make_question('stack', 'runtime_prt_err');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        $this->render();

        // Process a validate request.
        $this->process_submission(array('ans1' => '[2*sin(x)*y=1,x+y=1]', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('Result', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '[2*sin(x)*y=1,x+y=1]');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the incorrect answer.
        $this->process_submission(array('ans1' => '[2*sin(x)*y=1,x+y=1]', 'ans1_val' => '[2*sin(x)*y=1,x+y=1]', '-submit' => 1));

        // Verify.
        $this->check_current_state(question_state::$invalid);
        $this->check_current_mark(null);
        $this->check_prt_score('Result', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '[2*sin(x)*y=1,x+y=1]');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('Result');
        $this->check_output_does_not_contain_stray_placeholders();
        // Note from version 5.37.0 of Maxima the precise form of the error message changed.

        $this->check_current_output(
                new question_pattern_expectation('/following error: algsys: /'),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

    }

    public function test_test0_validate_then_submit_wrong_answer_default_penalty() {
        // Create the stack question based on 'test0'.
        $q = test_question_maker::make_question('stack', 'test0');
        $sans1 = new stack_cas_casstring('ans1');
        $sans1->get_valid('t');
        $tans1 = new stack_cas_casstring('2');
        $tans1->get_valid('t');
        $node1 = new stack_potentialresponse_node($sans1, $tans1, 'EqualComAss');
        $node1->add_branch(0, '=', 0, 0.3, 1, '', FORMAT_HTML, 'firsttree-1-F');
        $node1->add_branch(1, '=', 1, 0.3, -1, '', FORMAT_HTML, 'firsttree-1-T');
        $sans2 = new stack_cas_casstring('ans1');
        $sans2->get_valid('t');
        $tans2 = new stack_cas_casstring('3');
        $tans2->get_valid('t');
        $node2 = new stack_potentialresponse_node($sans2, $tans2, 'EqualComAss');
        $node2->add_branch(0, '=', 0, 0.3, -1, '', FORMAT_HTML, 'firsttree-2-F');
        // This is the point of the test: we explicitly set a zero penalty here.
        $node2->add_branch(1, '=', 0.5, 0.3, -1, '', FORMAT_HTML, 'firsttree-2-T');
        $q->prts['firsttree'] = new stack_potentialresponse_tree('firsttree', '', false, 1, null, array($node1, $node2), 0);
        $this->start_attempt_at_question($q, 'adaptive', 1);

        $this->render();

        // Process a validate request.
        $this->process_submission(array('ans1' => '3', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);

        $this->render();
        $this->check_output_contains_text_input('ans1', '3');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the correct answer.
        $this->process_submission(array('ans1' => '3', 'ans1_val' => '3', '-submit' => 1));
        // Verify.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0.5);
        $this->check_answer_note('firsttree', 'ATEqualComAss: (AlgEquiv:false). | firsttree-1-F | firsttree-2-T');
        $this->check_prt_score('firsttree', 0.5, 0.3);
        $this->render();
        $this->check_output_contains_text_input('ans1', '3');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();

        // Submit again and check penalty.
        $this->process_submission(array('ans1' => '2', 'ans1_val' => '3', '-submit' => 1));
        $this->check_current_state(question_state::$todo);
        // Mark from previous attempt is non-zero, even at the validate stage.
        $this->check_current_mark(0.5);
        $this->check_prt_score('firsttree', null, null);

        $this->process_submission(array('ans1' => '2', 'ans1_val' => '2', '-submit' => 1));
        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(0.7);
        $this->check_answer_note('firsttree', 'firsttree-1-T');
        $this->check_prt_score('firsttree', 1, 0);
        $this->render();
        $this->check_output_contains_text_input('ans1', '2');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_test0_validate_then_submit_wrong_answer_explicit_penalty() {

        // Create the stack question 'test0'.
        $q = test_question_maker::make_question('stack', 'test0');
        // Modify the PRT to that the penalty on the false branch is 0.1.
        $sans = new stack_cas_casstring('ans1');
        $sans->get_valid('t');
        $tans = new stack_cas_casstring('2');
        $tans->get_valid('t');
        $node = new stack_potentialresponse_node($sans, $tans, 'EqualComAss');
        $node->add_branch(0, '=', 0, 0.1, -1, '', FORMAT_HTML, 'firsttree-1-F');
        $node->add_branch(1, '=', 1, 0.1, -1, '', FORMAT_HTML, 'firsttree-1-T');
        $q->prts['firsttree'] = new stack_potentialresponse_tree('firsttree', '', false, 1, null, array($node), 0);
        $this->start_attempt_at_question($q, 'adaptive', 1);

        $this->render();

        // Process a validate request.
        $this->process_submission(array('ans1' => '3', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);

        $this->render();
        $this->check_output_contains_text_input('ans1', '3');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the correct answer.
        $this->process_submission(array('ans1' => '3', 'ans1_val' => '3', '-submit' => 1));

        // Verify.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->check_prt_score('firsttree', 0, 0.1);
        $this->render();
        $this->check_output_contains_text_input('ans1', '3');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();

        // Submit again and check penalty.
        $this->process_submission(array('ans1' => '2', 'ans1_val' => '3', '-submit' => 1));
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->check_prt_score('firsttree', null, null);

        $this->process_submission(array('ans1' => '2', 'ans1_val' => '2', '-submit' => 1));
        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(0.9);
        $this->check_prt_score('firsttree', 1, 0);
        $this->render();
        $this->check_output_contains_text_input('ans1', '2');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_test0_validate_then_submit_wrong_answer_no_penalty() {
        // This test creates a situation where we have partial credit, but the attempt
        // accrues no penalty.  This makes use of the PRT "penalty" field.

        // Create the stack question based on 'test0'.
        $q = test_question_maker::make_question('stack', 'test0');
        $sans1 = new stack_cas_casstring('ans1');
        $sans1->get_valid('t');
        $tans1 = new stack_cas_casstring('2');
        $tans1->get_valid('t');
        $node1 = new stack_potentialresponse_node($sans1, $tans1, 'EqualComAss');
        $node1->add_branch(0, '=', 0, 0.1, 1, '', FORMAT_HTML, 'firsttree-1-F');
        $node1->add_branch(1, '=', 1, 0.3, -1, '', FORMAT_HTML, 'firsttree-1-T');
        $sans2 = new stack_cas_casstring('ans1');
        $sans2->get_valid('t');
        $tans2 = new stack_cas_casstring('3');
        $tans2->get_valid('t');
        $node2 = new stack_potentialresponse_node($sans2, $tans2, 'EqualComAss');
        $node2->add_branch(0, '=', 0, 0.2, -1, '', FORMAT_HTML, 'firsttree-2-F');
        // This is the point of the test: we explicitly set a zero penalty here.
        $node2->add_branch(1, '=', 0.5, 0, -1, '', FORMAT_HTML, 'firsttree-2-T');
        $q->prts['firsttree'] = new stack_potentialresponse_tree('firsttree', '', false, 1, null, array($node1, $node2), 0);
        $this->start_attempt_at_question($q, 'adaptive', 1);

        $this->render();

        // Process a validate request.
        $this->process_submission(array('ans1' => '3', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);

        $this->render();
        $this->check_output_contains_text_input('ans1', '3');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the correct answer.
        $this->process_submission(array('ans1' => '3', 'ans1_val' => '3', '-submit' => 1));
        // Verify.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0.5);
        $this->check_answer_note('firsttree', 'ATEqualComAss: (AlgEquiv:false). | firsttree-1-F | firsttree-2-T');
        // This is the point of the test: we expect a zero penalty here in the 3rd argument.
        $this->check_prt_score('firsttree', 0.5, 0);
        $this->render();
        $this->check_output_contains_text_input('ans1', '3');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();

        // Submit again and check penalty.
        $this->process_submission(array('ans1' => '2', 'ans1_val' => '3', '-submit' => 1));
        $this->check_current_state(question_state::$todo);
        // Mark from previous attempt is non-zero, even at the validate stage.
        $this->check_current_mark(0.5);
        $this->check_prt_score('firsttree', null, null);

        $this->process_submission(array('ans1' => '2', 'ans1_val' => '2', '-submit' => 1));
        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(1);
        $this->check_answer_note('firsttree', 'firsttree-1-T');
        $this->check_prt_score('firsttree', 1, 0);
        $this->render();
        $this->check_output_contains_text_input('ans1', '2');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_test0_validate_then_submit_two_wrong_answers_one_no_penalty() {
         // This test creates a situation where we have partial credit, but the attempt
         // accrues no penalty.  This makes use of the PRT "penalty" field.

        // Create the stack question based on 'test0'.
        $q = test_question_maker::make_question('stack', 'test0');
        $sans1 = new stack_cas_casstring('ans1');
        $sans1->get_valid('t');
        $tans1 = new stack_cas_casstring('2');
        $tans1->get_valid('t');
        $node1 = new stack_potentialresponse_node($sans1, $tans1, 'EqualComAss');
        $node1->add_branch(0, '=', 0, 0.1, 1, '', FORMAT_HTML, 'firsttree-1-F');
        $node1->add_branch(1, '=', 1, 0.3, -1, '', FORMAT_HTML, 'firsttree-1-T');
        $sans2 = new stack_cas_casstring('ans1');
        $sans2->get_valid('t');
        $tans2 = new stack_cas_casstring('3');
        $tans2->get_valid('t');
        $node2 = new stack_potentialresponse_node($sans2, $tans2, 'EqualComAss');
        $node2->add_branch(0, '=', 0, 0.2, -1, '', FORMAT_HTML, 'firsttree-2-F');
        // This is the point of the test: we explicitly set a zero penalty here.
        $node2->add_branch(1, '=', 0.5, 0, -1, '', FORMAT_HTML, 'firsttree-2-T');
        $q->prts['firsttree'] = new stack_potentialresponse_tree('firsttree', '', false, 1, null, array($node1, $node2), 0);
        $this->start_attempt_at_question($q, 'adaptive', 1);

        $this->render();

        // Process a validate request.
        $this->process_submission(array('ans1' => '4', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);

        $this->render();
        $this->check_output_contains_text_input('ans1', '4');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the wrong response, which attracts a penalty.
        $this->process_submission(array('ans1' => '4', 'ans1_val' => '4', '-submit' => 1));
        // Verify.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->check_answer_note('firsttree',
                'ATEqualComAss: (AlgEquiv:false). | firsttree-1-F | ATEqualComAss: (AlgEquiv:false). | firsttree-2-F');
        $this->check_prt_score('firsttree', 0, 0.2);
        $this->render();
        $this->check_output_contains_text_input('ans1', '4');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a validate request of an incorrect response with no penalty.
        $this->process_submission(array('ans1' => '3', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->check_prt_score('firsttree', null, null);

        $this->render();
        $this->check_output_contains_text_input('ans1', '3');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the correct answer.
        $this->process_submission(array('ans1' => '3', 'ans1_val' => '3', '-submit' => 1));
        // Verify.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0.3);
        $this->check_answer_note('firsttree', 'ATEqualComAss: (AlgEquiv:false). | firsttree-1-F | firsttree-2-T');
        // This is the point of the test: we expect a zero penalty here in the 3rd argument.
        $this->check_prt_score('firsttree', 0.5, 0);
        $this->render();
        $this->check_output_contains_text_input('ans1', '3');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();

        // Submit again and check penalty.
        $this->process_submission(array('ans1' => '2', 'ans1_val' => '3', '-submit' => 1));
        $this->check_current_state(question_state::$todo);
        // Mark from previous attempt is non-zero, even at the validate stage.
        $this->check_current_mark(0.3);
        $this->check_prt_score('firsttree', null, null);

        $this->process_submission(array('ans1' => '2', 'ans1_val' => '2', '-submit' => 1));
        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(0.8);
        $this->check_answer_note('firsttree', 'firsttree-1-T');
        $this->check_prt_score('firsttree', 1, 0);
        $this->render();
        $this->check_output_contains_text_input('ans1', '2');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_units() {

        $q = test_question_maker::make_question('stack', 'units');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->assertEquals('adaptivemultipart',
                $this->quba->get_question_attempt($this->slot)->get_behaviour_name());
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/gravity/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Process a validate request.
        // Notice here we get away with including single letter question variables in the answer.
        $this->process_submission(array('ans1' => '9.8100*m/s^2', '-submit' => 1));
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '9.8100*m/s^2');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the incorrect answer (too many sig figs).
        $this->process_submission(array('ans1' => '9.8100*m/s^2', 'ans1_val' => '9.8100*m/s^2', '-submit' => 1));
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->check_prt_score('firsttree', 0, 0.2);
        $this->render();
        $this->check_output_contains_text_input('ans1', '9.8100*m/s^2');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a validate request.
        $this->process_submission(array('ans1' => '9.81*m/s^2', '-submit' => 1));
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '9.81*m/s^2');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the incorrect answer (too many sig figs).
        $this->process_submission(array('ans1' => '9.81*m/s^2', 'ans1_val' => '9.81*m/s^2', '-submit' => 1));
        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(0.8);
        $this->check_prt_score('firsttree', 1, 0);
        $this->render();
        $this->check_output_contains_text_input('ans1', '9.81*m/s^2');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_equiv_quad_1() {

        // Create the stack question 'equiv_quad'.
        $q = test_question_maker::make_question('stack', 'equiv_quad');
        $this->start_attempt_at_question($q, 'adaptive', 1);
        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->assertEquals('adaptivemultipart',
                $this->quba->get_question_attempt($this->slot)->get_behaviour_name());
        $this->render();
        $this->check_output_contains_textarea_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/Solve/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        $this->process_submission(array('ans1' => 'x^2-3*x+2=0', '-submit' => 1));
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_textarea_input('ans1', 'x^2-3*x+2=0');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        $this->process_submission(array('ans1' => "x^2-3*x+2=0\n(x-2)*(x-1)=0", '-submit' => 1));
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_textarea_input('ans1', "x^2-3*x+2=0\n(x-2)*(x-1)=0");
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        $this->process_submission(array('ans1' => "x^2-3*x+2=0\n(x-2)*(x-1)=0\nx=-1 and x=-2", '-submit' => 1));
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_textarea_input('ans1', "x^2-3*x+2=0\n(x-2)*(x-1)=0\nx=-1 and x=-2");
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $expectedvalidation = '\\[ \\begin{array}{lll} &x^2-3\cdot x+2=0& \\cr '.
            '\\color{green}{\Leftrightarrow}&\\left(x-2\\right)\cdot \\left(x-1\\right)=0& \\cr '.
            '\\color{red}{?}&\\left\{\\begin{array}{l}x=-1\\cr x=-2\\cr \\end{array}\\right.& \\cr \\end{array} \]';
        $this->assertContentWithMathsContains($expectedvalidation, $this->currentoutput);

        // @codingStandardsIgnoreStart
        $this->process_submission(array('ans1' => "x^2-3*x+2=0\n(x-2)*(x-1)=0\nx=-1 and x=-2",
                 'ans1_val' => "[x^2-3*x+2=0,(x-2)*(x-1)=0,x=-1 and x=-2]",'-submit' => 1));
        // @codingStandardsIgnoreEnd
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->check_prt_score('firsttree', 0, 0.2);
        $this->check_answer_note('firsttree', '[EMPTYCHAR,EQUIVCHAR,QMCHAR] | firsttree-1-F');
        $this->render();
        $this->check_output_contains_textarea_input('ans1', "x^2-3*x+2=0\n(x-2)*(x-1)=0\nx=-1 and x=-2");
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_stray_placeholders();

        $this->process_submission(array('ans1' => "x^2-3*x+2=0\n(x-2)*(x-1)=0\nx=1 or x=2", '-submit' => 1));
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_textarea_input('ans1', "x^2-3*x+2=0\n(x-2)*(x-1)=0\nx=1 or x=2");
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $expectedvalidation = '\\[ \\begin{array}{lll} &x^2-3\\cdot x+2=0& \\cr '.
                '\\color{green}{\\Leftrightarrow}&\\left(x-2\\right)\\cdot \\left(x-1\\right)=0& \\cr '.
                '\\color{green}{\\Leftrightarrow}&x=1\\,{\\mbox{ or }}\\, x=2& \\cr \\end{array} \\]';
        $this->assertContentWithMathsContains($expectedvalidation, $this->currentoutput);

        // @codingStandardsIgnoreStart
        $this->process_submission(array('ans1' => "x^2-3*x+2=0\n(x-2)*(x-1)=0\nx=1 or x=2",
                 'ans1_val' => "[x^2-3*x+2=0,(x-2)*(x-1)=0,x=1 or x=2]",'-submit' => 1));
        // @codingStandardsIgnoreEnd
        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(0.8);
        $this->check_prt_score('firsttree', 1, 0);
        $this->check_answer_note('firsttree', '[EMPTYCHAR,EQUIVCHAR,EQUIVCHAR] | firsttree-1-T');
        $this->render();
        $this->check_output_contains_textarea_input('ans1', "x^2-3*x+2=0\n(x-2)*(x-1)=0\nx=1 or x=2");
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_stray_placeholders();
        $expectedvalidation = '\\[ \\begin{array}{lll} &x^2-3\\cdot x+2=0& \\cr '.
                '\\color{green}{\\Leftrightarrow}&\\left(x-2\\right)\\cdot \\left(x-1\\right)=0& \\cr '.
                '\\color{green}{\\Leftrightarrow}&x=1\\,{\\mbox{ or }}\\, x=2& \\cr \\end{array} \\]';
        $this->assertContentWithMathsContains($expectedvalidation, $this->currentoutput);
    }

    public function test_equiv_quad_first_line() {

        // Create the stack question 'equiv_quad'.
        $q = test_question_maker::make_question('stack', 'equiv_quad');

        // Add in the option to force a particular first line.
        $q->inputs['ans1'] = stack_input_factory::make(
                'equiv', 'ans1', 'ta', null,
                array('boxWidth' => 20, 'forbidFloats' => false, 'options' => 'firstline'));

        $this->start_attempt_at_question($q, 'adaptive', 1);
        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->assertEquals('adaptivemultipart',
                $this->quba->get_question_attempt($this->slot)->get_behaviour_name());
        $this->render();
        $this->check_output_contains_textarea_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/Solve/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Get first line wrong.
        $this->process_submission(array('ans1' => 'x^2-3*x+1=0', '-submit' => 1));
        $this->check_current_state(question_state::$invalid);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_textarea_input('ans1', 'x^2-3*x+1=0');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Get first line right.
        $this->process_submission(array('ans1' => 'x^2-3*x+2=0', '-submit' => 1));
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_textarea_input('ans1', 'x^2-3*x+2=0');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Get first line right up to commutativity.
        $this->process_submission(array('ans1' => '2+x^2-3*x=0', '-submit' => 1));
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_textarea_input('ans1', '2+x^2-3*x=0');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Get first line right up to algebraic equivalence.  This is not enough!
        $this->process_submission(array('ans1' => '(x-1)*(x-2)=0', '-submit' => 1));
        $this->check_current_state(question_state::$invalid);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_textarea_input('ans1', '(x-1)*(x-2)=0');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_equiv_quad_hideequiv() {

        // Create the stack question 'equiv_quad'.
        $q = test_question_maker::make_question('stack', 'equiv_quad');

        // Add in the option to suppress equivalence feedback.
        $q->inputs['ans1'] = stack_input_factory::make(
                'equiv', 'ans1', 'ta', null,
                array('boxWidth' => 20, 'forbidFloats' => false, 'options' => 'hideequiv'));

        $this->start_attempt_at_question($q, 'adaptive', 1);
        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->assertEquals('adaptivemultipart',
                $this->quba->get_question_attempt($this->slot)->get_behaviour_name());
        $this->render();
        $this->check_output_contains_textarea_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/Solve/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        $this->process_submission(array('ans1' => "x^2-3*x+2=0\n(x-2)*(x-1)=0", '-submit' => 1));
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_textarea_input('ans1', "x^2-3*x+2=0\n(x-2)*(x-1)=0");
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        // This non-trivial sumbission should be shown without equivalence symbols.
        $expectedvalidation = '\\[ \\begin{array}{lll}x^2-3\cdot x+2=0& \\cr '.
                '\\left(x-2\\right)\cdot \\left(x-1\\right)=0& \\cr \\end{array} \]';
        $this->assertContentWithMathsContains($expectedvalidation, $this->currentoutput);
    }

    public function test_checkbox_empty() {

        // Create the stack question 'equiv_quad'.
        $q = test_question_maker::make_question('stack', 'checkbox_all_empty');

        $this->start_attempt_at_question($q, 'adaptive', 1);
        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->assertEquals('adaptivemultipart',
                $this->quba->get_question_attempt($this->slot)->get_behaviour_name());
        $this->render();
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/Which of/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
                );
    }

    public function test_test0_do_not_show_penalties() {

        // Create the stack question 'test0'.
        $q = test_question_maker::make_question('stack', 'test0');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->assertEquals('adaptivemultipart',
                $this->quba->get_question_attempt($this->slot)->get_behaviour_name());
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/What is/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Process a validate request.
        $this->process_submission(array('ans1' => '3', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '3');
        $this->check_output_contains_input_validation('ans1');
        // Since the answer is a number there are no variables.
        $this->check_output_does_not_contain_lang_string('studentValidation_listofvariables',
                'qtype_stack', '\( \left[ x \right]\)');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submition of an incorrect answer.
        $this->process_submission(array('ans1' => '3', 'ans1_val' => '3', '-submit' => 1));

        // Verify.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->check_prt_score('firsttree', 0, 0.3);

        $this->displayoptions->marks = question_display_options::MARK_AND_MAX;
        $this->render();
        $this->check_output_contains_text_input('ans1', '3');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_output_contains_lang_string('gradingdetails', 'quiz', array('raw' => '0.00', 'max' => '1.00'));
        $this->check_output_contains_lang_string('gradingdetailspenalty', 'quiz', '0.30');

        // Change the display options.
        $this->displayoptions->marks = question_display_options::MAX_ONLY;
        $this->render();
        $this->check_output_contains_text_input('ans1', '3');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_output_does_not_contain_lang_string('gradingdetails', 'quiz', array('raw' => '0.00', 'max' => '1.00'));
        $this->check_output_does_not_contain_lang_string('gradingdetailspenalty', 'quiz', '0.30');
    }
}
