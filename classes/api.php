<?php

define('MYGENGO_URL_BASE','http://api.mygengo.com/v1/');

class MyGengoApi
{
	private $apiKey;
	private $privateKey;

	function __construct()
	{
		$cfg = new MyGengoConfig();
		
		$this->apiKey = $cfg->apiKey();
		$this->privateKey = $cfg->privateKey();
	}

	private function error($msg)
	{
		return (object)array('opstat' => 'error','err' => (object)array('msg' => $msg));
	}

	private function perform($m,$cmd,$opts)
	{
		$resp = FALSE;

		if($m == 'GET')
			$resp = $this->get(MYGENGO_URL_BASE . $cmd,$opts);
		else if($m == 'POST')
			$resp = $this->post(MYGENGO_URL_BASE . $cmd,$opts);
		else if($m == 'PUT')
			$resp = $this->put(MYGENGO_URL_BASE . $cmd,$opts);
		else if($m == 'DELETE')
			$resp = $this->delete(MYGENGO_URL_BASE . $cmd,$opts);
		else
			return $this->error('Unknown HTTP method "' . $m . '"');

		if($resp != FALSE)
		{
			$resp = json_decode($resp);
			if($resp == NULL)
				return $this->error('Invalid response');
		}
		else
			$this->error('Response empty');
	
		return $resp;
	}

	private function get($url,$opts)
	{
		$opts['ts'] = gmdate('U');
		$opts['api_key'] = $this->apiKey;
		$opts['format'] = 'json';
		ksort($opts);

		$ret = http_build_query($opts);
		$opts['api_sig'] = hash_hmac('sha1',$ret,$this->privateKey);
	
		$ret = '';
		if(extension_loaded('curl'))
		{
			$curl = curl_init();
			curl_setopt($curl,CURLOPT_URL,$url . '?' . http_build_query($opts));
			curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl,CURLOPT_HTTPHEADER,array('Accept: application/json'));
			curl_setopt($curl,CURLOPT_HEADER, false);

			$ret = curl_exec($curl);
			curl_close($curl);
		}
		else
		{
			$hdr = '';
			eZHTTPTool::parseHTTPResponse(eZHTTPTool::sendHTTPRequest($url . '?' . http_build_query($opts),80,false,'none',false),$hdr,$ret);
		}
		return $ret;
	}

	private function post($url,$opts)
	{
		$opts['ts'] = gmdate('U');
		$opts['api_key'] = $this->apiKey;
		ksort($opts);

		$opts['api_sig'] = hash_hmac('sha1',json_encode($opts),$this->privateKey);

		$ret = '';
		if(extension_loaded('curl'))
		{
			$ch = curl_init($url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$opts);
			curl_setopt($ch,CURLOPT_HTTPHEADER,array('Accept: application/json'));
			$ret = curl_exec($ch);
			curl_close($ch);
		}
		else
		{
			$query = '';
			foreach($opts as $k => $v)
				$query .= urlencode($k) . '=' . urlencode($v) . '&';

    	$hdr = '';
			$tgt = parse_url($url);

			$sock = fsockopen($tgt['host'],80);
			fputs($sock,'POST ' . $tgt['path'] . " HTTP/1.1\r\n" .
				"Host: " . $tgt['host'] . "\r\n" .
				"Accept: application/json\r\n" .
				"Content-type: application/x-www-form-urlencoded\r\n" .
				"Content-length: " . strlen($query) . "\r\n" .
				"Pragma: no-cache\r\n" .
				"Connection: close\r\n\r\n" . $query);

			$resp = '';
			while(!feof($sock))
				$resp .= fgets($sock,1024);
			fclose($sock);
	
			eZHTTPTool::parseHTTPResponse($resp,$hdr,$ret);
		}
		
		return $ret;
	}

	private function delete($url,$opts)
	{
		$opts['ts'] = gmdate('U');
		$opts['api_key'] = $this->apiKey;
		ksort($opts);

		$ret = http_build_query($opts);
		$opts['api_sig'] = hash_hmac('sha1',$ret,$this->privateKey);

		$ret = '';
		if(extension_loaded('curl'))
		{
			$curl = curl_init();
			curl_setopt($curl,CURLOPT_URL,$url . '?' . http_build_query($opts));
			curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl,CURLOPT_HTTPHEADER,array('Accept: application/json'));
			curl_setopt($curl,CURLOPT_HEADER, false);
			curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'DELETE');
	
			$ret = curl_exec($curl);
			curl_close($curl);
		}
		else
		{
    	$hdr = '';
			$tgt = parse_url($url);

			$sock = fsockopen($tgt['host'],80);
			fputs($sock,'DELETE ' . $tgt['path'] . '?' . http_build_query($opts) . " HTTP/1.1\r\n" .
				"Host: " . $tgt['host'] . "\r\n" .
				"Accept: application/json\r\n" .
				"Pragma: no-cache\r\n" .
				"Connection: close\r\n\r\n");

			$resp = '';
			while(!feof($sock))
				$resp .= fgets($sock,1024);
			fclose($sock);
	
			eZHTTPTool::parseHTTPResponse($resp,$hdr,$ret);
		}

		return $ret;
	}
	
	private function put($url,$opts)
	{
		$opts['_method'] = "put";
		return $this->post($url,$opts);
	}

	function languages()
	{
		$names = $this->perform('GET','translate/service/languages',array());
		$cost = $this->perform('GET','translate/service/language_pairs',array());

		if($names->opstat == 'ok' && $cost->opstat == 'ok')
		{
			$ret = array();
		
			foreach($names->response as &$i)
				$ret[$i->lc] = $i;

			foreach($cost->response as &$l)
				$ret[$l->lc_src]->lc_tgt[$l->lc_tgt][] = $l;

			return $ret;
		}
		else
			return $names->err->msg;
	}

	function balance()
	{
		$json = $this->perform('GET','account/balance',array());

		if($json->opstat == 'ok')
			return $json->response->credits;
		else
			return $json->err->msg;
	}

	function tplBalance()
	{
		return array('result' => $this->balance());
	}

	public function post_job($slug,$body,$lc_src,$lc_tgt,$tier,$comment,$auto_appr)
	{
		$json = $this->perform('POST','translate/job',array('data' => json_encode(array(
			'job' => array(
				'body_src' => $body,
				'lc_src' => $lc_src,
				'lc_tgt' => $lc_tgt,
				'tier' => $tier,
				'slug' => $slug,
				'comment' => $comment,
				'auto_approve' => $auto_appr)))));
	
		if($json->opstat == 'ok')
		{
			$row = new MyGengoJob();
			$row->from_json($json->response->job);
			$row->sync();

			$c = $this->get_comments($json->response->job->job_id);

			$cmnt = new MyGengoComment();
			$cmnt->from_json($c[0],$json->response->job->job_id);
			$cmnt->sync();

			return $row;
		}
		else
			throw new Exception($json->err->msg);
	}

	function jobs()
	{
		$json = $this->perform('GET','translate/jobs',array());
	
		if($json->opstat == 'ok')
			return $json->response;
		else
			return $json->err->msg;
	}

	public function get_job($jid)
	{
		$json = $this->perform('GET',sprintf('translate/job/%s',$jid),array());

		if($json->opstat == 'ok')
			return $json->response->job;
		else
			return $json->err->msg;
	}

	public function get_comments($jid)
	{
		$json = $this->perform('GET',sprintf('translate/job/%s/comments',$jid),array());
	
		if($json->opstat == 'ok')
			return $json->response->thread;
		else
			return $json->err->msg;
	}

	public function cancel($jid)
 	{
		$json = $this->perform('DELETE',sprintf('translate/job/%s',$jid),array());
	
		if($json->opstat == 'ok')
			return true;
		else
			return false;
	}

	public function feedback($jid)
	{
		$json = $this->perform('GET',sprintf('translate/job/%s/feedback',$jid),array());
			
		if($json->opstat == 'ok')
			return $json->response->feedback;
		else
			return $json;
	}

	public function approve($jid,$rate,$trans,$mygengo,$public)
	{
		$json = $this->perform('PUT',sprintf('translate/job/%s',$jid),array(
			'data' => json_encode(array(
				'action' => 'approve', 
				'rating' => $rate,
				'for_translator' => $trans,
				'for_mygengo' => $mygengo,
				'public' => $public))));
	
		if($json->opstat == 'ok')
		{
			$row = MyGengoJob::fetch($jid);
			if(count($row) > 0)
			{
				$row[0]->from_json($this->get_job($jid));
				$row[0]->sync();
			}

			return true;
		}
		else
			throw new Exception($json->err->msg);
	}

	public function preview($jid)
	{
		$opts = array();
		$opts['ts'] = gmdate('U');
		$opts['api_key'] = $this->apiKey;
		ksort($opts);

		$ret = http_build_query($opts);
		$opts['api_sig'] = hash_hmac('sha1',$ret,$this->privateKey);

		return MYGENGO_URL_BASE . 'translate/job/' . $jid . '/preview' . '?' . http_build_query($opts);
	}

	public function comment($jid,$body)
	{
		$json = $this->perform('POST',sprintf('translate/job/%s/comment',$jid),array(
			'data' => json_encode(array(
				'body' => $body))));
		
		if($json->opstat == 'ok')
		{
			foreach($this->get_comments($jid) as $c)
			{
				if(count(MyGengoComment::fetch(array('ctime' => $c->ctime, 'jid' => $jid, 'body' => $c->body))) == 0)
				{
					$cmnt = new MyGengoComment();
					$cmnt->from_json($c,$jid);
					$cmnt->sync();
				}
			}
			return true;
		}
		else
			return $json->err->msg;
	}

	public function reject($jid,$follow_up,$reason,$comment,$captcha)
	{
		$json = $this->perform('PUT',sprintf('translate/job/%s',$jid),array(
			'data' => json_encode(array(
				'action' => 'reject', 
				'reason' => $reason,
				'comment' => $comment,
				'follow_up' => $follow_up,
				'captcha' => $captcha))));
	
		if($json->opstat == 'ok')
		{
			$row = MyGengoJob::fetch($jid);
			if(count($row) > 0)
			{
				$row[0]->from_json($this->get_job($jid));
				$row[0]->sync();
			}
			return true;
		}
		else
		{
			$row = MyGengoJob::fetch($jid);
			if(count($row) > 0)
			{
				$row[0]->setAttribute('captcha_url',$json->err->msg[1]);
				$row[0]->sync();
			}
			return false;
		}
	}

	public function correct($jid,$comment)
	{
		$json = $this->perform('PUT',sprintf('translate/job/%s',$jid),array(
			'data' => json_encode(array(
				'action' => 'revise', 
				'comment' => $comment))));
	
		if($json->opstat == 'ok')
		{
			$row = MyGengoJob::fetch($jid);
			if(count($row) > 0)
			{
				$row[0]->from_json($this->get_job($jid));
				$row[0]->sync();
			}
			return true;
		}
		else
			return false;
	}
}
?>
