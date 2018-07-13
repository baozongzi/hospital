<?php
use JokeChat\Util\ActiveRecord;
use JokeChat\Util\Dump;
/*
 * Created on 2016-5-19
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class action extends app
{
	public function display()
	{
		$action = $this->ev->url(3);
		if(!method_exists($this,$action))
		$action = "index";
		$this->$action();
		exit;
	}

	private function selectexams()
	{
		$search = $this->ev->get('search');
		$useframe = $this->ev->get('useframe');
		$target = $this->ev->get('target');
		$page = $this->ev->get('page');
		$page = $page > 0?$page:1;
		$this->pg->setUrlTarget('modal-body" class="ajax');
		$args = array();
		if($search)
		{
			if($search['subjectid'])$args[] = array("AND","examsubject = :examsubject",'examsubject',$search['subjectid']);
		}
		if(!count($args))$args = 1;
		$exams = $this->exam->getExamSettingList($page,10,$args);
		$subjects = $this->basic->getSubjectList();
		$this->tpl->assign('subjects',$subjects);
		$this->tpl->assign('target',$target);
		$this->tpl->assign('exams',$exams);
		$this->tpl->display('exams_ajax');
	}

	private function delexam()
	{
		$examid = $this->ev->get('examid');
		$page = $this->ev->get('page');
		$this->exam->delExamSetting($examid);
		$message = array(
			'statusCode' => 200,
			"message" => "操作成功",
			"callbackType" => "forward",
		    "forwardUrl" => "index.php?exam-master-exams&page={$page}{$u}"
		);
		$this->G->R($message);
	}

	private function preview()
	{
		$examid = $this->ev->get('examid');
		$r = $this->exam->getExamSettingById($examid);
		$this->tpl->assign("setting",$r);
		if($r['examtype'] == 2)
		{
			$questions = array();
			$questionrows = array();
			foreach($r['examquestions'] as $key => $p)
			{
				$qids = '';
				$qrids = '';
				if($p['questions'])$qids = trim($p['questions']," ,");
				if($qids)
				$questions[$key] = $this->exam->getQuestionListByIds($qids);
				if($p['rowsquestions'])$qrids = trim($p['rowsquestions']," ,");
				if($qrids)
				{
					$qrids = explode(",",$qrids);
					foreach($qrids as $t)
					{
						$qr = $this->exam->getQuestionRowsById($t);
						if($qr)
						$questionrows[$key][$t] = $qr;
					}
				}
			}
			$args['examsessionquestion'] = array('questions'=>$questions,'questionrows'=>$questionrows);
			$args['examsessionsetting'] = $r;
			$args['examsessionstarttime'] = TIME;
			$args['examsession'] = $r['exam'];
			$args['examsessionscore'] = 0;
			$args['examsessiontime'] = $r['examsetting']['examtime'];
			$args['examsessiontype'] = 2;
			$args['examsessionkey'] = $r['examid'];
			$args['examsessionissave'] = 0;
		}
		else
		{
			$args['examsessionquestion'] = array('questions'=>$r['examquestions']['questions'],'questionrows'=>$r['examquestions']['questionrows']);
			$args['examsessionsetting'] = $r;
			$args['examsessionstarttime'] = TIME;
			$args['examsession'] = $r['exam'];
			$args['examsessiontime'] = $r['examsetting']['examtime'];
			$args['examsessiontype'] = 2;
			$args['examsessionkey'] = $r['examid'];
		}
		$questype = $this->basic->getQuestypeList();
		$this->tpl->assign('questype',$questype);
		$this->tpl->assign("sessionvars",$args);
		$this->tpl->display('exams_paper');
	}

	private function modifypaper()
	{
		$examid = $this->ev->get('examid');
		$questionid = $this->ev->get('questionid');
		$qrid = $this->ev->get('qrid');
		$r = $this->exam->getExamSettingById($examid);
		$questypes = $this->basic->getQuestypeList();
		$this->tpl->assign("questypes",$questypes);
		if($this->ev->get('modifypaper'))
		{
			$args = $this->ev->get('args');
			$targs = $this->ev->get('targs');
			$q = null;
			if($qrid)
			{
				foreach($r['examquestions']['questionrows'] as $tkey => $tp)
				{
					foreach($tp as $key => $p)
					{
						if($p['qrid'] == $qrid)
						{
							$r['examquestions']['questionrows'][$tkey][$key]['qrquestion'] = $args['qrquestion'];
							$q = 1;
							break;
						}
						if($q)break;
					}
					if($q)break;
				}
			}
			else
			{
				foreach($r['examquestions']['questions'] as $tkey => $tp)
				{
					foreach($tp as $key => $p)
					{
						if($p['questionid'] == $questionid)
						{
							$args['questionid'] = $questionid;
							$questype = $this->basic->getQuestypeById($args['questiontype']);
							if($questype['questsort'])$choice = 0;
							else $choice = $questype['questchoice'];
							$args['questionanswer'] = $targs['questionanswer'.$choice];
							$r['examquestions'][$tkey][$key] = $args;
							$q = 1;
							break;
						}
					}
					if($q)break;
				}

				foreach($r['examquestions']['questionrows'] as $qkey => $tp)
				{
					foreach($tp as $tkey => $ttp)
					{
						foreach($ttp['data'] as $key => $p)
						{
							if($p['questionid'] == $questionid)
							{
								$args['questionid'] = $questionid;
								$questype = $this->basic->getQuestypeById($args['questiontype']);
								if($questype['questsort'])$choice = 0;
								else $choice = $questype['questchoice'];
								$args['questionanswer'] = $targs['questionanswer'.$choice];
								$r['examquestions']['questionrows'][$qkey][$tkey]['data'][$key] = $args;
								$q = 1;
								break;
							}
						}
						if($q)break;
					}
					if($q)break;
				}
			}
			$this->exam->modifyExamSetting($examid,array('examquestions' => $r['examquestions']));
			$message = array(
				'statusCode' => 200,
				"message" => "操作成功",
				"callbackType" => "forward",
			    "forwardUrl" => "index.php?exam-master-exams-preview&examid=".$examid
			);
			$this->G->R($message);
		}
		else
		{
			$question = null;
			if($qrid)
			{
				foreach($r['examquestions']['questionrows'] as $tp)
				{
					foreach($tp as $p)
					{
						if($p['qrid'] == $qrid)
						{
							$question = $p;
							break;
						}
						if($question)break;
					}
					if($question)break;
				}
			}
			else
			{
				foreach($r['examquestions']['questions'] as $tp)
				{
					foreach($tp as $p)
					{
						if($p['questionid'] == $questionid)
						{
							$question = $p;
							break;
						}
					}
					if($question)break;
				}
				foreach($r['examquestions']['questionrows'] as $tp)
				{
					foreach($tp as $ttp)
					{
						foreach($ttp['data'] as $p)
						{
							if($p['questionid'] == $questionid)
							{
								$question = $p;
								break;
							}
						}
						if($question)break;
					}
					if($question)break;
				}
			}
			$this->tpl->assign("examid",$examid);
			$this->tpl->assign("questionid",$questionid);
			$this->tpl->assign("qrid",$qrid);
			$this->tpl->assign("question",$question);
			$this->tpl->display('exams_modifypaper');
		}
	}

	private function outcsv()
	{
		$this->files = $this->G->make('files');
		$examid = $this->ev->get('examid');
		$exam = $this->exam->getExamSettingById($examid);
		$questypes = $this->basic->getQuestypeList();
		$data = array();
		foreach($exam['examquestions'] as $tp)
		{
			foreach($tp as $p)
			$data[] = array(iconv("UTF-8","GBK//IGNORE",$questypes[$p['questiontype']]['questchar']),iconv("UTF-8","GBK//IGNORE",html_entity_decode($p['question'])),iconv("UTF-8","GBK//IGNORE",html_entity_decode($p['questionselect'])),iconv("UTF-8","GBK//IGNORE",$p['questionselectnumber']),iconv("UTF-8","GBK//IGNORE",html_entity_decode($p['questionanswer'])),iconv("UTF-8","GBK//IGNORE",html_entity_decode($p['questiondescribe'])));
		}
		$fname = 'data/exams/'.TIME.'-'.$examid.'-score.csv';
		if($this->files->outCsv($fname,$data))
		$message = array(
			'statusCode' => 200,
			"message" => "成绩导出成功，转入下载页面，如果浏览器没有相应，请<a href=\"{$fname}\">点此下载</a>",
		    "callbackType" => 'forward',
		    "forwardUrl" => "{$fname}"
		);
		else
		$message = array(
			'statusCode' => 300,
			"message" => "成绩导出失败"
		);
		$this->G->R($message);
	}

	private function ajax()
	{
		switch($this->ev->url(4))
		{
			default:
			$subjectid = $this->ev->get('subjectid');
			$type = $this->ev->get('type');
			if($subjectid)
			{
				$basic = $this->basic->getBasicBySubjectId($subjectid);
				$questypes = $this->basic->getQuestypeList();
				$this->tpl->assign('questypes',$questypes);
				$this->tpl->assign("type",$type);
				$this->tpl->assign("subjectid",$subjectid);
				$this->tpl->assign("basic",$basic);
				$this->tpl->display('exams_ajaxsetting');
			}
		}
	}

	private function del()
	{
		$page = $this->ev->get('page');
		$examid = $this->ev->get('examid');
		$this->exam->delExamSetting($examid);
		$message = array(
			'statusCode' => 200,
			"message" => "操作成功",
			"callbackType" => "forward",
		    "forwardUrl" => "index.php?exam-master-exams&page={$page}{$u}"
		);
		$this->G->R($message);
	}

	private function autopage()
	{
		if($this->ev->get('submitsetting'))
		{
			$args = $this->ev->get('args');
			$args['examsetting'] = $args['examsetting'];
			$args['examauthorid'] = $this->_user['sessionuserid'];
			$args['examauthor'] = $this->_user['sessionusername'];
			$args['examtype'] = 1;

			foreach($args['examsetting']['questype'] as $key => $p)
			{
				if(!$args['examsetting']['questypelite'][$key])
				{
					unset($args['examsetting']['questype'][$key],$args['examquestions'][$key]);
				}
			}

			$this->exam->addExamSetting($args);
			$message = array(
				'statusCode' => 200,
				"message" => "操作成功",
			    "forwardUrl" => "index.php?exam-master-exams&page={$page}{$u}"
			);
			$this->G->R($message);
		}
		else
		{
			$subjects = $this->basic->getSubjectList();
			$questypes = $this->basic->getQuestypeList();
			$this->tpl->assign('questypes',$questypes);
			$this->tpl->assign('subjects',$subjects);
			$this->tpl->display('exams_auto');
		}
	}

	private function selfpage()
	{
		if($this->ev->get('submitsetting'))
		{
			$args = $this->ev->get('args');
			$args['examsetting'] = $args['examsetting'];
			$args['examauthorid'] = $this->_user['sessionuserid'];
			$args['examauthor'] = $this->_user['sessionusername'];
			$args['examtype'] = 2;
			$args['examquestions'] = $args['examquestions'];

			foreach($args['examsetting']['questype'] as $key => $p)
			{
				if(!$args['examsetting']['questypelite'][$key])
				{
					unset($args['examsetting']['questype'][$key],$args['examquestions'][$key]);
				}
			}


			$id = $this->exam->addExamSetting($args);
			$message = array(
				'statusCode' => 200,
				"message" => "操作成功",
				"callbackType" => "forward",
			    "forwardUrl" => "index.php?exam-master-exams-examself&examid={$id}&page={$page}{$u}"
			);
			$this->G->R($message);
		}
		else
		{
			$subjects = $this->basic->getSubjectList();
			$questypes = $this->basic->getQuestypeList();
			$this->tpl->assign('questypes',$questypes);
			$this->tpl->assign('subjects',$subjects);
			$this->tpl->display('exams_self');
		}
	}

	private function temppage()
	{
		if($this->ev->get('submitsetting'))
		{
			$args = $this->ev->get('args');
			$uploadfile = $this->ev->get('uploadfile');
			if(!$uploadfile)
			{
				$message = array(
					'statusCode' => 300,
					"message" => "请上传即时试卷试题"
				);
				$this->G->R($message);
			}
			$args['examsetting'] = $args['examsetting'];
			$args['examauthorid'] = $this->_user['sessionuserid'];
			$args['examauthor'] = $this->_user['sessionusername'];
			$args['examtype'] = 3;
			setlocale(LC_ALL,'zh_CN');
			$handle = fopen($uploadfile,"r");
			$questions = array();
			$rindex = 0;
			$index = 0;
			while ($data = fgetcsv($handle))
			{
				$targs = array();
				$question = $data;
				if(count($question) >= 5)
				{
					$isqr = intval(trim($question[6]," \n\t"));
					if($isqr)
					{
						$istitle = intval(trim($question[7]," \n\t"));;
						if($istitle)
						{
							$rindex ++;
							$targs['qrid'] = 'qr_'.$rindex;
							$targs['qrtype'] = $question[0];
							$targs['qrquestion'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK","UTF-8//IGNORE",trim(nl2br($question[1])," \n\t"))));
							$targs['qrcreatetime'] = TIME;
							$questionrows[$targs['qrtype']][intval($rindex - 1)] = $targs;
						}
						else
						{
							$index ++;
							$targs['questionid'] = 'q_'.$index;
							$targs['questiontype'] = $question[0];
							$targs['question'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK","UTF-8//IGNORE",trim(nl2br($question[1])," \n\t"))));
							$targs['questionselect'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK","UTF-8//IGNORE",trim(nl2br($question[2])," \n\t"))));
							if(!$targs['questionselect'] && $targs['questiontype'] == 3)
							$targs['questionselect'] = '<p>A、对<p><p>B、错<p>';
							$targs['questionselectnumber'] = $question[3];
							$targs['questionanswer'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK","UTF-8//IGNORE",trim($question[4]," \n\t"))));
							$targs['questiondescribe'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK","UTF-8//IGNORE",trim($question[5]," \n\t"))));
							$targs['questioncreatetime'] = TIME;
							$questionrows[$targs['questiontype']][intval($rindex - 1)]['data'][] = $targs;
							//$qustionnumber++;
						}
					}
					else
					{
						$index++;
						$targs['questionid'] = 'q_'.$index;
						$targs['questiontype'] = $question[0];
						$targs['question'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK","UTF-8//IGNORE",trim(nl2br($question[1])," \n\t"))));
						$targs['questionselect'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK","UTF-8//IGNORE",trim(nl2br($question[2])," \n\t"))));
						if(!$targs['questionselect'] && $targs['questiontype'] == 3)
						$targs['questionselect'] = '<p>A、对<p><p>B、错<p>';
						$targs['questionselectnumber'] = intval($question[3]);
						$targs['questionanswer'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK","UTF-8//IGNORE",trim($question[4]," \n\t"))));
						$targs['questiondescribe'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK","UTF-8//IGNORE",trim($question[5]," \n\t"))));
						$targs['questioncreatetime'] = TIME;
						$questions[$targs['questiontype']][] = $targs;
						//$qustionnumber++;
					}
				}
			}
			$args['examquestions'] = array('questions' => $questions,'questionrows' => $questionrows);
			//$args['examsetting']['questype'][1]['number'] = $qustionnumber;
			//$args['examsetting']['questype'][1]['score'] = intval(100/$qustionnumber);
			$id = $this->exam->addExamSetting($args);
			$message = array(
				'statusCode' => 200,
				"message" => "操作成功",
				"callbackType" => "forward",
			    "forwardUrl" => "index.php?exam-master-exams-examself&examid={$id}&page={$page}{$u}"
			);
			$this->G->R($message);
		}
		else
		{
			$subjects = $this->basic->getSubjectList();
			$questypes = $this->basic->getQuestypeList();
			$this->tpl->assign('questypes',$questypes);
			$this->tpl->assign('subjects',$subjects);
			$this->tpl->display('exams_temp');
		}
	}

	private function selected()
	{
		$show = $this->ev->get('show');
			$questionids = trim($this->ev->get('questionids')," ,");
			$rowsquestionids = trim($this->ev->get('rowsquestionids')," ,");
			if(!$questionids)$questionids = '0';
			if(!$rowsquestionids)$rowsquestionids = '0';
			$questions = $this->exam->getQuestionListByArgs(array(array("AND","questionstatus = 1"),array("AND","find_in_set(questionid,:questionid)",'questionid',$questionids)));
			$rowsquestions = array();
			$rowsquestionids = explode(',',$rowsquestionids);
			foreach($rowsquestionids as $p)
			{
				if($p)
				$rowsquestions[$p] = $this->exam->getQuestionRowsByArgs(array(array("AND","qrstatus = 1"),array("AND","qrid = :qrid",'qrid',$p)));
			}
			$this->tpl->assign('rowsquestions',$rowsquestions);
			$this->tpl->assign('questions',$questions);
			$this->tpl->assign('show',$show);
			$this->tpl->display('exams_selected');
	}

	private function selectquestions()
	{
		$search = $this->ev->get('search');
		$useframe = $this->ev->get('useframe');
		$page = $this->ev->get('page');
		$page = $page > 0?$page:1;
		$this->pg->setUrlTarget('modal-body" class="ajax');
		if(!$search['questionisrows'])
		{
			$args = array(array("AND","quest2knows.qkquestionid = questions.questionid"),array("AND","questions.questionstatus = '1'"),array("AND","questions.questionparent = 0"),array("AND","quest2knows.qktype = 0") );
			if($search['keyword'])
			{
				$args[] = array("AND","questions.question LIKE :question",'question','%'.$search['keyword'].'%');
			}
			if($search['knowsids'])
			{
				$args[] = array("AND","find_in_set(questions.questionknowsid,:questionknowsid)",'questionknowsid',$search['knowsids']);
			}
			if($search['stime'])
			{
				$args[] = array("AND","questions.questioncreatetime >= :squestioncreatetime",'squestioncreatetime',strtotime($search['stime']));
			}
			if($search['etime'])
			{
				$args[] = array("AND","questions.questioncreatetime <= :equestioncreatetime",'equestioncreatetime',strtotime($search['etime']));
			}
			if($search['questiontype'])
			{
				$args[] = array("AND","questions.questiontype = :questiontype",'questiontype',$search['questiontype']);
			}
			if($search['questionlevel'])
			{
				$args[] = array("AND","questions.questionlevel = :questionlevel",'questionlevel',$search['questionlevel']);
			}
			if($search['questionknowsid'])
			{
				$args[] = array("AND","quest2knows.qkknowsid = :qkknowsid",'qkknowsid',$search['questionknowsid']);
			}
			else
			{
				$tmpknows = '0';
				if($search['questionsectionid'])
				{
					$knows = $this->section->getKnowsListByArgs(array(array("AND","knowsstatus = 1"),array("AND","knowssectionid = :knowssectionid",'knowssectionid',$search['questionsectionid'])));
					foreach($knows as $p)
					{
						if($p['knowsid'])$tmpknows .= ','.$p['knowsid'];
					}
					$args[] = array("AND","find_in_set(quest2knows.qkknowsid,:qkknowsid)",'qkknowsid' ,$tmpknows);
				}
				elseif($search['questionsubjectid'])
				{
					$knows = $this->section->getAllKnowsBySubject($search['questionsubjectid']);
					foreach($knows as $p)
					{
						if($p['knowsid'])$tmpknows .= ','.$p['knowsid'];
					}
					$args[] = array("AND","find_in_set(quest2knows.qkknowsid,:qkknowsid)",'qkknowsid',$tmpknows);
				}
				else
				{
					$knows = $this->section->getAllKnowsBySubjects($this->teachsubjects);
					foreach($knows as $p)
					{
						if($p['knowsid'])$tmpknows .= ','.$p['knowsid'];
					}
					$args[] = array("AND","find_in_set(quest2knows.qkknowsid,:qkknowsid)",'qkknowsid',$tmpknows);
				}
			}

			$questions = $this->exam->getQuestionsList($page,10,$args);
		}
		else
		{
			$args = array(array("AND","quest2knows.qkquestionid = questionrows.qrid"),array("AND","questionrows.qrstatus = '1'"));
			if($search['keyword'])
			{
				$args[] = array("AND","questionrows.qrquestion LIKE :qrquestion",'qrquestion','%'.$search['keyword'].'%');
			}
			if($search['questiontype'])
			{
				$args[] = array("AND","questionrows.qrtype = :qrtype",'qrtype',$search['questiontype']);
			}
			if($search['stime'])
			{
				$args[] = array("AND","questionrows.qrtime >= :sqrtime",'sqrtime',strtotime($search['stime']));
			}
			if($search['etime'])
			{
				$args[] = array("AND","questionrows.qrtime <= :eqrtime",'eqrtime',strtotime($search['etime']));
			}
			if($search['qrlevel'])
			{
				$args[] = array("AND","questionrows.qrlevel = :qrlevel",'qrlevel',$search['qrlevel']);
			}
			if($search['questionknowsid'])
			{
				$args[] = array("AND","quest2knows.qkknowsid = :qkknowsid",'qkknowsid',$search['questionknowsid']);
			}
			else
			{
				$tmpknows = '0';
				if($search['questionsectionid'])
				{
					$knows = $this->section->getKnowsListByArgs(array(array("AND","knowsstatus = 1"),array("AND","knowssectionid = :knowssectionid",'knowssectionid',$search['questionsectionid'])));
					foreach($knows as $p)
					{
						if($p['knowsid'])$tmpknows .= ','.$p['knowsid'];
					}
					$args[] = array("AND","find_in_set(quest2knows.qkknowsid,:qkknowsid)",'qkknowsid' ,$tmpknows);
				}
				elseif($search['questionsubjectid'])
				{
					$knows = $this->section->getAllKnowsBySubject($search['questionsubjectid']);
					foreach($knows as $p)
					{
						if($p['knowsid'])$tmpknows .= ','.$p['knowsid'];
					}
					$args[] = array("AND","find_in_set(quest2knows.qkknowsid,:qkknowsid)",'qkknowsid',$tmpknows);
				}
				else
				{
					$knows = $this->section->getAllKnowsBySubjects($this->teachsubjects);
					foreach($knows as $p)
					{
						if($p['knowsid'])$tmpknows .= ','.$p['knowsid'];
					}
					$args[] = array("AND","find_in_set(quest2knows.qkknowsid,:qkknowsid)",'qkknowsid',$tmpknows);
				}
			}
			$questions = $this->exam->getQuestionrowsList($page,10,$args);
		}
		if($useframe)$questions['pages'] = str_replace('&useframe=1','',$questions['pages']);
		$questypes = $this->basic->getQuestypeList();
		$sections = $this->section->getSectionListByArgs(array(array("AND","sectionsubjectid = :sectionsubjectid",'sectionsubjectid',$search['questionsubjectid'])));
		$knows = $this->section->getKnowsListByArgs(array(array("AND","knowsstatus = 1"),array("AND","knowssectionid = :knowssectionid",'knowssectionid',$search['questionsectionid'])));
		//$this->tpl->assign('subjects',$subjects);
		$this->tpl->assign('search',$search);
		$this->tpl->assign('sections',$sections);
		$this->tpl->assign('knows',$knows);
		$this->tpl->assign('questypes',$questypes);
		$this->tpl->assign('questiontype',$search['questiontype']);
		$this->tpl->assign('questions',$questions);
		$this->tpl->assign('useframe',$useframe);
		$this->tpl->display('selectquestions');
	}

	private function modify()
	{
		$search = $this->ev->get('search');
		$examid = $this->ev->get('examid');
		$exam = $this->exam->getExamSettingById($examid);
		if($this->ev->get('submitsetting'))
		{
			$args = $this->ev->get('args');
			$args['examsetting'] = $args['examsetting'];
			if($exam['examtype'] == 3)
			{
				$uploadfile = $this->ev->get('uploadfile');
				if($uploadfile)
				{
					setlocale(LC_ALL,'zh_CN');
					$handle = fopen($uploadfile,"r");
					$questions = array();
					$index = 0;
					$rindex = 0;
					while($data = fgetcsv($handle))
					{
						$targs = array();
						$question = $data;
						if(count($question) >= 5)
						{
							$isqr = intval(trim($question[6]," \n\t"));
							if($isqr)
							{
								$istitle = intval(trim($question[7]," \n\t"));;
								if($istitle)
								{
									$rindex ++;
									$targs['qrid'] = 'qr_'.$rindex;
									$targs['qrtype'] = $question[0];
									$targs['qrquestion'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK","UTF-8//IGNORE",trim(nl2br($question[1])," \n\t"))));
									$targs['qrcreatetime'] = TIME;
									$questionrows[$targs['qrtype']][intval($rindex - 1)] = $targs;
								}
								else
								{
									$index ++;
									$targs['questionid'] = 'q_'.$index;
									$targs['questiontype'] = $question[0];
									$targs['question'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK","UTF-8//IGNORE",trim(nl2br($question[1])," \n\t"))));
									$targs['questionselect'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK","UTF-8//IGNORE",trim(nl2br($question[2])," \n\t"))));
									if(!$targs['questionselect'] && $targs['questiontype'] == 3)
									$targs['questionselect'] = '<p>A、对<p><p>B、错<p>';
									$targs['questionselectnumber'] = intval($question[3]);
									$targs['questionanswer'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK","UTF-8//IGNORE",trim($question[4]," \n\t"))));
									$targs['questiondescribe'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK","UTF-8//IGNORE",trim($question[5]," \n\t"))));
									$targs['questioncreatetime'] = TIME;
									$questionrows[$targs['questiontype']][intval($rindex - 1)]['data'][] = $targs;
									//$qustionnumber++;
								}
							}
							else
							{
								$index++;
								$targs['questionid'] = 'q_'.$index;
								$targs['questiontype'] = $question[0];
								$targs['question'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK","UTF-8//IGNORE",trim(nl2br($question[1])," \n\t"))));
								/**
								$ei = md5($targs['question']);
								if($isexit[$ei])
								{
									$message = array(
										'statusCode' => 300,
										"message" => "试题重复，该试题是:".$targs['question']
									);
									$this->G->R($message);
								}
								else
								$isexit[$ei] = 1;
								**/
								$targs['questionselect'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK","UTF-8//IGNORE",trim(nl2br($question[2])," \n\t"))));
								if(!$targs['questionselect'] && $targs['questiontype'] == 3)
								$targs['questionselect'] = '<p>A、对<p><p>B、错<p>';
								$targs['questionselectnumber'] = $question[3];
								$targs['questionanswer'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK","UTF-8//IGNORE",trim($question[4]," \n\t"))));
								$targs['questiondescribe'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK","UTF-8//IGNORE",trim($question[5]," \n\t"))));
								$targs['questioncreatetime'] = TIME;
								$questions[$targs['questiontype']][] = $targs;
								//$qustionnumber++;
							}
						}
					}
					$args['examquestions'] = array('questions' => $questions,'questionrows' => $questionrows);
				}
			}
			else
			$args['examquestions'] = $args['examquestions'];
			foreach($args['examsetting']['questype'] as $key => $p)
			{
				if(!$args['examsetting']['questypelite'][$key])
				{
					unset($args['examsetting']['questype'][$key],$args['examquestions'][$key]);
				}
			}

			$this->exam->modifyExamSetting($examid,$args);
			$message = array(
				'statusCode' => 200,
				"message" => "操作成功",
				"callbackType" => "forward",
			    "forwardUrl" => "index.php?exam-master-exams&page={$page}{$u}"
			);
			$this->G->R($message);
		}
		else
		{
			$subjects = $this->basic->getSubjectList();
			$questypes = $this->basic->getQuestypeList();
			foreach($exam['examquestions'] as $key => $p)
			{
				$exam['examnumber'][$key] = $this->exam->getExamQuestionNumber($p);
			}
			foreach($exam['examsetting']['questypelite'] as $key => $p)
			{
				if(!$subjects[$exam['examsubject']]['subjectsetting']['questypes'][$key])
				{
					$exam['examsetting']['questypelite'][$key] = 0;
				}
			}
			foreach($subjects[$exam['examsubject']]['subjectsetting']['questypes'] as $key => $p)
			{
				if(!$exam['examsetting']['questypelite'][$key])
				{
					$exam['examsetting']['questypelite'][$key] = 1;
				}
			}
			$this->tpl->assign('search',$search);
			$this->tpl->assign('subjects',$subjects);
			$this->tpl->assign('exam',$exam);
			$this->tpl->assign('questypes',$questypes);
			if($exam['examtype'] == 1)
			$this->tpl->display('exams_modifyauto');
			elseif($exam['examtype'] == 2)
			$this->tpl->display('exams_modifyself');
			else
			$this->tpl->display('exams_modifytemp');
		}
	}

	private function index()
	{
		$search = $this->ev->get('search');
		$page = $this->ev->get('page');
		$page = $page > 0?$page:1;
		$args = array();
		if($search)
		{
			if($search['examsubject'])$args[] = array("AND","examsubject = :examsubject",'examsubject',$search['examsubject']);
			if($search['examtype'])$args[] = array("AND","examtype = :examtype",'examtype',$search['examtype']);
		}
		if(!count($args))$args = 1;
		$exams = $this->exam->getExamSettingList($page,10,$args);
		$subjects = $this->basic->getSubjectList();
		$this->tpl->assign('subjects',$subjects);
		$this->tpl->assign('exams',$exams);
		$this->tpl->display('exams');
	}
	
	// 用户成绩列表 以及筛选
	private function score()
	{
		$examid 	= $_REQUEST['examid'];
		$where 		= array('ehexamid' => $examid);
		$arModel 	= new ActiveRecord();
		$userids 	= $arModel->where($where)
							  ->groupBy("ehuserid")
							  ->get("x2_examhistory",null,'ehuserid');
		
		if (array_key_exists("search", $_REQUEST)) 
		{
			$search 	= $_REQUEST['search'];
			$stime 		= $search['stime'];
			$etime 		= $search['etime'];
			// 过滤无用信息
			if (!empty($stime) && !empty($etime)) {
				$this->tpl->assign('search',$search);
				$where['ehstarttime'] = array('between'=>array(strtotime($stime),strtotime($etime)));
			}
		}
		$list 		= array();
		foreach ($userids as $k => $v)
		{
			$where['ehuserid'] 	= $v['ehuserid'];
			$info 	= $arModel->join("x2_user", "ehuserid = userid")
			->where($where)
			->orderBy("ehscore",'desc')
			->getOne("x2_examhistory");
			if (!empty($info))
			{
				$ehsetting 	= unserialize($info['ehsetting']);
				$info['passscore']	= $ehsetting['examsetting']['passscore'] ? $ehsetting['examsetting']['passscore'] : "未设置";
				$list[] 		= $info;
			}
		}
		$this->tpl->assign('examid',$examid);
		$this->tpl->assign('list',$list);
		$this->tpl->display("score");
	}
	
	/**
	 * 导出excel @jokechat by 2017年7月10日18:07:59
	 * @return string
	 */
	private function export()
	{
		$examid 	= $_REQUEST['examid'];
		$where 		= array('ehexamid' => $examid);
		$arModel 	= new ActiveRecord();
		$userids 	= $arModel->where($where)
		->groupBy("ehuserid")
		->get("x2_examhistory",null,'ehuserid');

		if (array_key_exists("search", $_REQUEST))
		{
			$search 	= $_REQUEST['search'];
			$stime 		= $search['stime'];
			$etime 		= $search['etime'];
			// 过滤无用信息
			if (!empty($stime) && !empty($etime)) {
				$this->tpl->assign('search',$search);
				$where['ehstarttime'] = array('between'=>array(strtotime($stime),strtotime($etime)));
			}	
		}
		
		$excel 			= new Excel();
		$sheet_title 	= array('0' => "考试课程名称",'1'=> '用户名','2'=>'用户得分','3'=>'及格线','4'=>'开考时间','5'=>'考试用时');
		$list 			= array();
		
		// 秒数转分钟
		$time2min 	= 	function($seconds){
			if ($seconds > 60) {
				return (intval($seconds/60))."分钟".(intval($seconds%60))."秒";
			}
			if ($seconds < 60) {
				return $seconds."秒";
			}
		};
		
		foreach ($userids as $k => $v)
		{
			$where['ehuserid'] 	= $v['ehuserid'];
			$info 	= $arModel->join("x2_user", "ehuserid = userid")
			->where($where)
			->orderBy("ehscore",'desc')
			->getOne("x2_examhistory");
			if (!empty($info))
			{
				$ehsetting 	= unserialize($info['ehsetting']);
				$info['passscore']	= $ehsetting['examsetting']['passscore'] ? $ehsetting['examsetting']['passscore'] : "未设置";
				$list[$k]['ehexam'] 		= $info['ehexam'];
				$list[$k]['username'] 		= $info['username'];
				$list[$k]['ehscore'] 		= $info['ehscore'];
				$list[$k]['passscore'] 		= $info['passscore'];
				$list[$k]['ehstarttime'] 	= date('Y-m-d H:i:s',$info['ehstarttime']);
				$list[$k]['ehtime'] 		= $time2min($info['ehtime']);
			}
		}
		$filename 		= "report";
		$excel->create_excel($sheet_title, $list, $filename);
	}
}


?>
