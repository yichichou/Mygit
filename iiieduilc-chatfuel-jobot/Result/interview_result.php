<?php
if (isset($_GET['FB_ID'])) {
    $FB_ID = $_GET['FB_ID'];

    $servername = "localhost";
    $username = "FB";
    $password = "12345678";
    $dbname = "fb_interview";
    $str ="";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
	//個人的資料
    $sql = "SELECT * FROM `fb_chatbot_result` WHERE `FB_ID` = $FB_ID ORDER BY id DESC limit 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
        $str =json_encode($row);
        $homepage = file_get_contents('interview_result.html');
        echo $homepage;
        }
    } else {
        echo "0 results";
    }
	
	
	//skill_score資料
    $sql_1 = "SELECT FB_ID, UserName, skill_score FROM `fb_chatbot_result` WHERE FB_ID != $FB_ID ORDER BY skill_score DESC ";
    $result_1 = $conn->query($sql_1);
	
    if ($result_1->num_rows > 0) {
        // output data of each row
		$rows_1 = array();
		while($r = mysqli_fetch_assoc($result_1)) {
			//echo json_encode($r);
			$rows_1[] = $r;

        }
			$str_1 = json_encode($rows_1);
			//echo $str_1 ;
    } else {
        echo "0 results";
    }
	
	//experience_score資料
    $sql_2 = "SELECT FB_ID, UserName, experience_score FROM `fb_chatbot_result` WHERE FB_ID != $FB_ID ORDER BY experience_score DESC";
    $result_2 = $conn->query($sql_2);
	
    if ($result_2->num_rows > 0) {
        // output data of each row
		$rows_2 = array();
		while($r = mysqli_fetch_assoc($result_2)) {
			//echo json_encode($r);
			$rows_2[] = $r;

        }
			$str_2 = json_encode($rows_2);
			//echo $str_1 ;
    } else {
        echo "0 results";
    }
	
	//trait_score 資料
    $sql_3 = "SELECT FB_ID, UserName, trait_score FROM `fb_chatbot_result` WHERE FB_ID != $FB_ID ORDER BY trait_score DESC";
    $result_3 = $conn->query($sql_3);
	
    if ($result_3->num_rows > 0) {
        // output data of each row
		$rows_3 = array();
		while($r = mysqli_fetch_assoc($result_3)) {
			//echo json_encode($r);
			$rows_3[] = $r;

        }
			$str_3 = json_encode($rows_3);
			//echo $str_1 ;
    } else {
        echo "0 results";
    }

    //skill_score 前均後標
    $sql_skill_score = "SELECT skill_score FROM `fb_chatbot_result` ORDER BY skill_score DESC ";
    $result_skill_score = $conn->query($sql_skill_score);
    
    if ($result_skill_score->num_rows > 0) {
        // output data of each row
        $rows_skill_score = array();
        while($r = mysqli_fetch_assoc($result_skill_score)) {
            //echo json_encode($r);
            $rows_skill_score[] = $r;

        }
            $str_skill_score = json_encode($rows_skill_score);
            //echo $str_skill_score ;
    } else {
        echo "0 results";
    }

    //experience_score 前均後標
    $sql_experience_score = "SELECT experience_score FROM `fb_chatbot_result` ORDER BY experience_score DESC";
    $result_experience_score = $conn->query($sql_experience_score);
    
    if ($result_experience_score->num_rows > 0) {
        // output data of each row
        $rows_experience_score = array();
        while($r = mysqli_fetch_assoc($result_experience_score)) {
            //echo json_encode($r);
            $rows_experience_score[] = $r;

        }
            $str_experience_score = json_encode($rows_experience_score);
            //echo $str_1 ;
    } else {
        echo "0 results";
    }

    //trait_score 前均後標
    $sql_trait_score = "SELECT trait_score FROM `fb_chatbot_result` ORDER BY trait_score DESC";
    $result_trait_score = $conn->query($sql_trait_score);
    
    if ($result_trait_score->num_rows > 0) {
        // output data of each row
        $rows_trait_score = array();
        while($r = mysqli_fetch_assoc($result_trait_score)) {
            //echo json_encode($r);
            $rows_trait_score[] = $r;

        }
            $str_trait_score = json_encode($rows_trait_score);
            //echo $str_1 ;
    } else {
        echo "0 results";
    }


}
?>
<script>
    var label = {
  "start": {
    "count": "5",
    "title": "start",
    "priorities": [{
      "txt": "Work"
    }, {
      "txt": "Time Sense"
    }, {
      "txt": "Dicipline"
    }, {
      "txt": "Confidence"
    }, {
      "txt": "CrossFunctional"
    }]
  }
};

    var str = "[" + <?php echo json_encode($str);?> + "]";
    var obj = JSON.parse(str);
    var score;
	document.getElementById('info').innerHTML = '<i class="fa fa-user-circle-o">' + "<h2>姓名：" + obj[0].UserName + "</h2>" + '<i class="fa fa-address-card-o"></i>' + "<h2>性別：" + obj[0].Gender + "</h2>" + '<i class="fa fa-envelope-o"></i>' + "<h2>Email：" + obj[0].Email + "</h2>";
	document.getElementById('picture').innerHTML ='<img src='+obj[0].Picture+'>';
	
	var pic_analysis_info=JSON.parse("["+obj[0].pic_analysis+"]");

    var eye_Json = JSON.parse('{"Eye": [{ "value": [ "通常具有敏銳觀察力，思慮縝密，行事小心，常帶有自我保護之心態，氣度顯得稍狹些。", "高度敏銳觀察力，思慮縝密，行事小心，常帶有自我保護之心態，氣度顯得稍狹些。" ] }, { "value": [ "感受力強，愛表現出風頭，為人爽朗熱誠，喜歡華美的事物，愛表現出風頭，帶些迷糊的性格。", "感受力強，愛表現出風頭，喜歡華美的事物，愛表現出風頭，因此也有些虛榮心態。" ] },        { "value": [ "處事鎮定，為人傲氣，凡事喜歡速戰速決，富於決斷力。", "處事鎮定，多能經過深思熟慮後再付諸行動。" ] },        { "value": [ "個性坦率，樂觀天真，喜歡浪漫事物，但佔有慾強，且帶些投機心態。", "個性坦率，樂觀而天真，善於表現自我，社交能力佳。" ] },        { "value": [ "善於觀察事物，好追根究底，多具才華，但也有些錙銖必較，講求實際。", "善於觀察、思考、分析與推理，喜歡用頭腦依自己的步調來解決問題。" ] },        { "value": [ "能夠吃苦耐勞，但易受人影響或擺布，一生小人較多。", "個性務實而穩重，待人誠懇而無心機。" ] },        { "value": [ "善於觀察、思考力毅力堅強，不易退縮，努力追求自我設定的目標。", "個性沉著穩健，冷靜而理智，積極且縝密，雖行事明快，卻較不擅長表達內心感受。" ] },        { "value": [ "感情豐富，較缺乏耐力，偏好感性事物，稍欠些許理性思維。", "交友廣闊，卻無選擇能力，屢屢招忌妒或易受人情方面的壓力及煩惱。" ] },        { "value": [ "為人多情也癡情，眼神雖具魅力，但一生多感情困擾，須慎選對象，以免遇人不淑。", "眼睛水汪汪，眼神極具魅力，但交友尤需慎選對象，以免遇人不淑。" ] },        { "value": [ "處事鎮定，行事溫和，態度穩健，是個很好的輔佐人才。", "具有良好的觀察力，個性謹慎小心，凡事設想周到。" ] },        { "value": [ "高傲、自負的心態，擅於掌握時機，即時發揮自我優點。", "多富於行動力，頭腦精敏靈活，處事爽快明朗。" ] },        { "value": [ "個性敏感好奇，領悟力強，頗具美感之天份，學習能力頗佳。", "感情豐富，深具異性緣及個人魅力。" ] },        { "value": [ "人生易逢爭端及險境，姻緣路上多障礙。", "屬於野心家，直覺敏銳，但很難以誠心交友，善惡觀念全憑自己的利益而定。" ] },        { "value": [ "是非判斷與常人不同，較不顧他人感受，具有一氣呵成的行事作風，也是個頗具爭議的人物。", "性格難明，倔強冷漠，心思深沉，為人處事常憑自我喜好。" ]} ]}');

    var eyebrow_Json = JSON.parse('{"Eyebrow": [{ "value": [ "感情豐富，具有濃厚的家族親情觀念。", "其人聰慧靈敏，個性細密，好觀察思考。" ] },{ "value": [ "較不懂人情世故，親人間的助力也較少。", "通常是個急性子，行事往往瞻前不顧後。" ] },{ "value": [ "好面子，喜結交四方之友，能得朋友助力。", "大多具有膽識和自信，也好爭強出鋒頭。" ] },{ "value": [ "樂於奉獻自我，易受到貴人或長輩的賞識提拔。", "個性溫良平順，心思細膩，重感情。" ] },{ "value": [ "重視家庭觀念，富於自信而剛強果決。", "其人頗具正義感，個性耿直，求好心切。" ] },{ "value": [ "自我主觀較強，勇於追求理想及目標，深具快速行動的能力。", "其人思路敏捷，善於動腦，頗具數理方面之才華。" ] },{ "value": [ "行事沉穩，深富人緣，易受到信任。", "為人重情守義，樂於助人，也易得人助。" ] },{ "value": [ "其些投機心態，情緒反覆，易給人壓力。", "其人頗擅營謀，具領導能力，但行事較顯誇張。" ] },{ "value": [ "膽識過人，但若眼泛凶，則人生易罹險惡境地。", "性格高尚，深富遠大理想及抱負，行事剛強。" ] },{ "value": [ "不計後果，但行事快速而決斷，深富旺盛之企圖心。", "其人意志堅強，不易和人妥協，常會感情用事。" ] }]}');

    var nose_Json = JSON.parse('{"Nose": [{ "value": [ "頭腦佳，重權勢，交友廣闊，愛面子，追求財富不遺餘力。", "精力充沛，多具氣魄及才幹，稍帶些投機心態。" ] },{ "value": [ "行事猶豫，中年運勢較為平淡，須多在工作上再加把勁。", "個性較保守平和，行事猶豫，多慮而敏感，不具野心。" ] },{ "value": [ "開銷頗大，積儲財源能力稍弱些，但個性也不太計較。", "個性直爽寬和，具包容力，待人熱誠大方，也好施捨。" ] },{ "value": [ "能愛惜物資，不會浪費，但器度略顯不足。", "個性圓巧，擅於守成，帶些利我主義，較現實，對賺錢有企圖心。" ] },{ "value": [ "不輕言妥協，防禦心強，有自負驕傲的個性。", "好勝倔強，多疑且善攻心計，喜歡新奇，易和他人競爭較量。" ] }]}');

    var mouth_Json = JSON.parse('{"Mouth": [{ "value": [ "善於調解他人紛爭，胸襟坦闊，比較屬於大器晚成型。", "個性溫和開朗，處世圓融，凡事身體力行。" ] },{ "value": [ "行事有恆，能默默付出，貢獻己力。", "心地善良隨和，富同情心，聰慧靈巧。" ] }, { "value": [ "好爭較，略帶些利己之心，雖情意稍顯淡薄，不太熱情，但人生多朝積極面去發展。", "思慮敏捷，富於機巧及才智，實事求是，應變能力頗強，常能舉一反三。" ] },{ "value": [ "為人誠摯且富人情味，也懂得自我付出，凡事樂天知足。", "個性篤實溫良，重感情，較不擅表達內心意圖" ] },{ "value": [ "講求原則，但也帶些保守，不易變通的性格。", "其人行事循規蹈矩，待人處事謹守本份。" ] },{ "value": [ "凡事提心吊膽，畏縮怕事，晚運較不順心。", "思慮單純，一生福祿稍淺薄，常覺人生煩惱不安。" ] },{ "value": [ "自我意識高漲，個性剛強固執，旁人的意見也較難聽的進去。", "性格帶些偏頗，好強詞奪理，言語行為常表裡不一。" ] },{ "value": [ "自我意識高漲，個性剛強固執，旁人的意見也較難聽的進去。", "性格帶些偏頗，好強詞奪理，言語行為常表裡不一。" ] },{ "value": [ "聰明具才華，能得朋友之信賴及仰重，如唇色紅潤明亮，更為晚運亨通之兆。", "冷艷的美，有魅力，氣質佳。愛恨分明，懂得上進，聰明有為，做事認真負責。" ] },{ "value": [ "然性格耿直，能耐苦勞，肯勇於面對挑戰並突破困境。", "為人偏執，行事態度強硬，不會輕易妥協。" ] }]}');

    var face_Json = JSON.parse('{"Face": [{ "value": [ "懂得適時表現自我能力，給人高傲感，經常無意中得罪人。", "多為頭腦精明，固執且自信，意志力堅強。" ] },{ "value": [ "追求理想也重物質慾望，如自行創業，部屬運佳，晚運可享安穩。", "個性厚實，古道熱腸，交友廣博，具有領導才能。" ] },{ "value": [ "好奇心強，反應靈敏，個性敏感，情緒反覆。", "口才好，人緣佳，見人說人話，見鬼說鬼話，足智多謀、反應靈敏、善於交際。" ] },{ "value": [ "感情豐富卻多變，通常具有藝術細胞和創造力，宜努力設定目標，並實踐貫徹到底。", "具有藝術細胞和創造力，有令人激賞的音樂及藝術天賦，因此創造力也很不錯。" ] },{ "value": [ "個性溫良敦厚，心胸寬和，待人圓融，性喜安逸閒適。", "一生多具偏財運，為人大方卻多能積財，晚景享優渥。" ] },{ "value": [ "性格沉穩篤定，中晚期大多開創自營，為白手起家型人物。", "為人腳踏實地，具有正義感，比較講求實力。" ] },{ "value": [ "為人機巧多計，但也喜怒好惡不定，行事往往無法長久持續。", "人生運途常半途而廢，中晚年宜求穩定。" ] },{ "value": [ "行事多能貫徹到底，中晚年發展事業較能得到較佳成果，具有良好的置產能力。", "其人具有良好的才智及膽識，富於決斷力及實踐精神。" ] },{ "value": [ "須謹慎擇友，缺乏部屬助力，晚年居所易變動不安定，須自求多福。", "運勢起伏大，易走偏門，抓不定人生的方向，姻緣可能多變。" ] }]}');


	 if(pic_analysis_info[0]!=null && pic_analysis_info[0].array[0].eye != null){
		var eye = pic_analysis_info[0].array[0].eye;
		var eyebrow = pic_analysis_info[0].array[0].eyebrow;
		var nose = pic_analysis_info[0].array[0].nose;
		var mouth = pic_analysis_info[0].array[0].mouth;
		var face = pic_analysis_info[0].array[0].face;

		document.getElementById('pic_analysis').innerHTML ='<p>'+eye_Json.Eye[eye].value[Math.floor(Math.random()*2)]+'</p>'+'<p>'+eyebrow_Json.Eyebrow[eyebrow].value[Math.floor(Math.random()*2)]+'</p>'+'<p>'+nose_Json.Nose[nose].value[Math.floor(Math.random()*2)]+'</p>'+'<p>'+mouth_Json.Mouth[mouth].value[Math.floor(Math.random()*2)]+'</p>'+'<p>'+face_Json.Face[face].value[Math.floor(Math.random()*2)]+'</p>';
	}
    else{
        document.getElementById('pic_analysis').innerHTML ='<p>你沒有拍出一個正確的人臉相片哦!!!啾咪^^</p>'
    } 
	
	var arr = ["Keep on going never give up. 勇往直前，決不放棄！","Success is the amplification of the merits. 成功是優點的發揮","Don't underestimate yourself. 不要小看自己", "Believe in yourself. 相信你自己！","You think you can, you can. 你認為你行，你就行。","I can because i think i can. 我行，因為我相信我行！","Winners do what losers don't want to do. 勝利者做失敗者不願意做的事！","Jack of all trades and master of none. 門門精通，樣樣稀鬆。","Whatever is worth doing is worth doing well. 任何值得做的事就值得把它做好！","Don't give up and don't give in. 不要放棄，不要言敗！","Zero in your target,and go for it. 從零開始，勇往直前！","Quitters never win and winners never quit. 退縮者永無勝利，勝利者永不退縮。" ,"You're uinique, nothing can replace you. 你舉世無雙，無人可以替代。","The best preparation for tomorrow is doing your best today. 對明天做好的準備就是今天做到最好！","Enrich your life today. Yesterday is history.tomorrow is mystery. 充實今朝，昨日已成過去，明天充滿神奇。","Nothing great was ever achieved without enthusiasm. 無熱情成就不了偉業。","The secret of success is constancy of purpose. 成功的秘訣在於持之於恆。","Pursue your object, be it what it will, steadily and indefatigably.  不管追求什麼目標，都應堅持不懈。","Never give up, Never lose the opportunity to succeed . 不放棄就有成功的機會。","Self-distrust is the cause of most of our failures. 我們絕大多數的失敗都是因為缺乏自信之故。","Energy and persistence conquer all things.能量和堅持可以征服一切事情。","Four short words sum up what has lifted most successful individuals above the crowd: a little bit more. 成功的秘訣就是四個簡單的字：多一點點。","A bold attempt is half success. 勇敢的嘗試是成功的一半。","I feel strongly that I can make it. 我堅信我一定能成功。","If you are doing your best,you will not have to worry about failure. 如果你竭盡全力，你就不用擔心失敗。","Don't try so hard, the best things come when you least expect them to. 不要著急，最好的總會在最不經意的時候出現。"];   
	var index = Math.floor((Math.random()*arr.length));   
	alert(arr[index]);   

	//var com=getRandomArrayElements(items, 1) ;
	document.getElementById('comment').innerHTML ='<p>'+arr[index]+'</p>';
	
    //DISC
    
    
    var chart = AmCharts.makeChart( "DISCRadarChart", {
      "type": "radar",
      "theme": "light",
      "dataProvider": [ {
        "country": "D",
        "litres": obj[0].DScore
      }, {
        "country": "I",
        "litres": obj[0].IScore
      }, {
        "country": "S",
        "litres": obj[0].SScore
      }, {
        "country": "C",
        "litres":obj[0].CScore
      } ],
      "startDuration": 2,
      "graphs": [ {
        "balloonText": "D-I-S-C",
        "bullet": "round",
        "lineThickness": 5,
        "valueField": "litres"
      } ],
      "categoryField": "country",
      "export": {
        "enabled": true
      }
    } );

    
	//前均後標
	var obj_skill_score = JSON.parse(<?php echo json_encode($str_skill_score);?>);
	var obj_experience_score = JSON.parse(<?php echo json_encode($str_experience_score);?>);
    var obj_trait_score = JSON.parse(<?php echo json_encode($str_trait_score);?>);
	
    score = obj[0].total_score;
    var chart = AmCharts.makeChart("pieChartdiv", {

        "type": "pie",
        "theme": "light",
        "dataProvider": [{
            "personality": "穩定者(ST)",
            "percentage": obj[0].ST
        }, {
            "personality": "協調者(SF)",
            "percentage": obj[0].SF
        }, {
            "personality": "激發者(NF)",
            "percentage": obj[0].NF
        }, {
            "personality": "夢想者(NT)",
            "percentage": obj[0].NT
        }],
        "valueField": "percentage",
        "titleField": "personality",
        "balloon": {
            "fixedPosition": true
        },
        "export": {
            "enabled": true
        }
    });

    var chart = AmCharts.makeChart("columnChartdiv", {
        "type": "serial",
        "theme": "light",
        "dataProvider": [{
            "questionNumber": "I",
            "score": obj[0].no1
        }, {
            "questionNumber": "II",
            "score": obj[0].no2
        }, {
            "questionNumber": "III",
            "score": obj[0].no3
        }, {
            "questionNumber": "IV",
            "score": obj[0].no4
        }, {
            "questionNumber": "V",
            "score": obj[0].no5
        }, {
            "questionNumber": "VI",
            "score": obj[0].no6
        }, {
            "questionNumber": "VII",
            "score": obj[0].no7
        }, {
            "questionNumber": "VIII",
            "score": obj[0].no8
        }, {
            "questionNumber": "IX",
            "score": obj[0].no9
        }, {
            "questionNumber": "X",
            "score": obj[0].no10
        }],
        "valueAxes": [{
            "gridColor": "#FFFFFF",
            "gridAlpha": 0.2,
            "dashLength": 0
        }],
        "valueAxes": [{
            "gridColor": "#FFFFFF",
            "gridAlpha": 0.2,
            "dashLength": 0
        }],
        "gridAboveGraphs": true,
        "startDuration": 1,
        "graphs": [{
            "balloonText": "[[category]]: <b>[[value]]</b>",
            "fillAlphas": 0.8,
            "lineAlpha": 0.2,
            "type": "column",
            "valueField": "score"
        }],
        "chartCursor": {
            "categoryBalloonEnabled": false,
            "cursorAlpha": 0,
            "zoomable": false
        },
        "categoryField": "questionNumber",
        "categoryAxis": {
            "gridPosition": "start",
            "gridAlpha": 0,
            "tickPosition": "start",
            "tickLength": 20
        },
        "export": {
            "enabled": true
        }

    });

    var gaugeChart = AmCharts.makeChart("totalChartdiv", {
        "type": "gauge",
        "theme": "light",
        "axes": [{
            "axisThickness": 1,
            "axisAlpha": 0.2,
            "tickAlpha": 0.2,
            "valueInterval": 10,
            "bands": [{
                "color": "#84b761",
                "endValue": 20,
                "innerRadius": "80%",
                "startValue": 0
            }, {
                "color": "#fdd400",
                "endValue": 40,
                "innerRadius": "80%",
                "startValue": 20
            }, {
                "color": "#cc4748",
                "endValue": 60,
                "innerRadius": "80%",
                "startValue": 40
            }],
            "bottomText": "",
            "bottomTextYOffset": -20,
            "endValue": 60
        }],
        "arrows": [{}],
        "export": {
            "enabled": true
        }
    });
	
	//skill_score資料rankSkill
	var str_11 =  <?php echo json_encode($str_1);?>;	
    var obj_1 = JSON.parse(str_11);
		if(obj_1!= null){
			var chart = AmCharts.makeChart("rankSkillChartdiv",{
				"title":"SkillRank",
				"type": "serial",
				"theme": "light",
				"dataProvider": [{
					"name": obj_1[0].UserName,
					"points": obj_1[0].skill_score,
					"color": "#7F8DA9",
					"bullet": "https://www.amcharts.com/lib/images/faces/E01.png"
				}, {
					"name": obj_1[1].UserName,
					"points": obj_1[1].skill_score,
					"color": "#FEC514",
					"bullet": "https://www.amcharts.com/lib/images/faces/C02.png"
				}, {
					"name": obj_1[2].UserName,
					"points": obj_1[2].skill_score,
					"color": "#DB4C3C",
					"bullet": "https://www.amcharts.com/lib/images/faces/D02.png"
				}, {
				"name": obj[0].UserName,
				"points": obj[0].skill_score,
				"color": "#DAF0FD",
				"bullet": obj[0].Picture
				}],
			
				"valueAxes": [{
					"maximum": obj_skill_score[0].skill_score*1.2,
					"minimum": 0,
					"axisAlpha": 0,
					"dashLength": 4,
					"position": "left"
				}],
				"startDuration": 1,
				"graphs": [{
					"balloonText": "<span style='font-size:13px;'>[[category]]: <b>[[value]]</b></span>",
					"bulletOffset": 10,
					"bulletSize": 52,
					"colorField": "color",
					"cornerRadiusTop": 8,
					"customBulletField": "bullet",
					"fillAlphas": 0.8,
					"lineAlpha": 0,
					"type": "column",
					"valueField": "points"
				}],
				"marginTop": 0,
				"marginRight": 0,
				"marginLeft": 0,
				"marginBottom": 0,
				"autoMargins": false,
				"categoryField": "name",
				"categoryAxis": {
					"axisAlpha": 0,
					"gridAlpha": 0,
					"inside": true,
					"tickLength": 0
				},
				"export": {
					"enabled": true
				}
			});

	}
		
	
		
	//Experiences_core資料rankExperience    rankExperiencechartdiv
	var str_22 =  <?php echo json_encode($str_2);?>;	
    var obj_2 = JSON.parse(str_22);
	
	if(obj_2!=null){
			var chart = AmCharts.makeChart("rankExperiencechartdiv",{
				"type": "serial",
				"theme": "light",
				"dataProvider": [{
					"name": obj_2[0].UserName,
					"points": obj_2[0].experience_score,
					"color": "#7F8DA9",
					"bullet": "https://www.amcharts.com/lib/images/faces/E01.png"
				}, {
					"name": obj_2[1].UserName,
					"points": obj_2[1].experience_score,
					"color": "#FEC514",
					"bullet": "https://www.amcharts.com/lib/images/faces/C02.png"
				}, {
					"name": obj_2[2].UserName,
					"points": obj_2[2].experience_score,
					"color": "#DB4C3C",
					"bullet": "https://www.amcharts.com/lib/images/faces/D02.png"
				}, {
				"name": obj[0].UserName,
				"points": obj[0].experience_score,
				"color": "#DAF0FD",
				"bullet": obj[0].Picture
				}],
			
				"valueAxes": [{
					"maximum": obj_experience_score[0].experience_score*1.2,
					"minimum": 0,
					"axisAlpha": 0,
					"dashLength": 4,
					"position": "left"
				}],
				"startDuration": 1,
				"graphs": [{
					"balloonText": "<span style='font-size:13px;'>[[category]]: <b>[[value]]</b></span>",
					"bulletOffset": 10,
					"bulletSize": 52,
					"colorField": "color",
					"cornerRadiusTop": 8,
					"customBulletField": "bullet",
					"fillAlphas": 0.8,
					"lineAlpha": 0,
					"type": "column",
					"valueField": "points"
				}],
				"marginTop": 0,
				"marginRight": 0,
				"marginLeft": 0,
				"marginBottom": 0,
				"autoMargins": false,
				"categoryField": "name",
				"categoryAxis": {
					"axisAlpha": 0,
					"gridAlpha": 0,
					"inside": true,
					"tickLength": 0
				},
				"export": {
					"enabled": true
				}
			});
		
	}
	
	//trait_core 資料rankTrait    rankTraitchartdiv
	var str_33 =  <?php echo json_encode($str_3);?>;	
    var obj_3 = JSON.parse(str_33);
	
	if(obj_3!=null){
		var chart = AmCharts.makeChart("rankTraitchartdiv",{
			"type": "serial",
			"theme": "light",
			"dataProvider": [{
				"name": obj_3[0].UserName,
				"points": obj_3[0].trait_score,
				"color": "#7F8DA9",
				"bullet": "https://www.amcharts.com/lib/images/faces/E01.png"
			}, {
				"name": obj_3[1].UserName,
				"points": obj_3[1].trait_score,
				"color": "#FEC514",
				"bullet": "https://www.amcharts.com/lib/images/faces/C02.png"
			}, {
				"name": obj_3[2].UserName,
				"points": obj_3[2].trait_score,
				"color": "#DB4C3C",
				"bullet": "https://www.amcharts.com/lib/images/faces/D02.png"
			}, {
			"name": obj[0].UserName,
			"points": obj[0].trait_score,
			"color": "#DAF0FD",
			"bullet": obj[0].Picture
			}],
		
			"valueAxes": [{
				"maximum": obj_trait_score[0].trait_score*1.2,
				"minimum": 0,
				"axisAlpha": 0,
				"dashLength": 4,
				"position": "left"
			}],
			"startDuration": 1,
			"graphs": [{
				"balloonText": "<span style='font-size:13px;'>[[category]]: <b>[[value]]</b></span>",
				"bulletOffset": 10,
				"bulletSize": 52,
				"colorField": "color",
				"cornerRadiusTop": 8,
				"customBulletField": "bullet",
				"fillAlphas": 0.8,
				"lineAlpha": 0,
				"type": "column",
				"valueField": "points"
			}],
			"marginTop": 0,
			"marginRight": 0,
			"marginLeft": 0,
			"marginBottom": 0,
			"autoMargins": false,
			"categoryField": "name",
			"categoryAxis": {
				"axisAlpha": 0,
				"gridAlpha": 0,
				"inside": true,
				"tickLength": 0
			},
			"export": {
				"enabled": true
			}
		});
	}
	
	var record=obj_skill_score.length;
	var rank1 =Math.ceil((record)/4);
	var rank3 =Math.ceil((record)/4*3);
	
	
	var chart = AmCharts.makeChart("SkillChartdiv", {
	"theme": "light",
    "type": "serial",
    "dataProvider": [{
        "name": obj[0].UserName,
        "startTime": obj[0].skill_score,
        "endTime": 0,
        "color": "#FF0F00"
    }, {
        "name": "高標",
        "startTime":  obj_skill_score[0].skill_score,
        "endTime":  obj_skill_score[rank1].skill_score,
        "color": "#FF9E01"
    }, {
        "name": "均標",
        "startTime": obj_skill_score[rank3].skill_score,
        "endTime": obj_skill_score[rank1].skill_score,
        "color": "#F8FF01"
    }, {
        "name": "低標",
        "startTime": 0,
        "endTime": obj_skill_score[rank3].skill_score,
        "color": "#04D215"
    }],
    "valueAxes": [{
        "axisAlpha": 0,
        "gridAlpha": 0.1
    }],
    "startDuration": 1,
    "graphs": [{
        "balloonText": "<b>[[category]]</b><br>starts at [[startTime]]<br>ends at [[endTime]]",
        "colorField": "color",
        "fillAlphas": 0.8,
        "lineAlpha": 0,
        "openField": "startTime",
        "type": "column",
        "valueField": "endTime"
    }],
    "rotate": true,
    "columnWidth": 1,
    "categoryField": "name",
    "categoryAxis": {
        "gridPosition": "start",
        "axisAlpha": 0,
        "gridAlpha": 0.1,
        "position": "left"
    },
    "export": {
    	"enabled": true
     }
});



var chart = AmCharts.makeChart("ExperienceChartdiv", {
	"theme": "light",
    "type": "serial",
    "dataProvider": [{
        "name": obj[0].UserName,
        "startTime": obj[0].experience_score,
        "endTime": 0,
        "color": "#FF0F00"
    }, {
        "name": "高標",
        "startTime":  obj_experience_score[0].experience_score,
        "endTime":  obj_experience_score[rank1].experience_score,
        "color": "#FF9E01"
    }, {
        "name": "均標",
        "startTime": obj_experience_score[rank3].experience_score,
        "endTime": obj_experience_score[rank1].experience_score,
        "color": "#F8FF01"
    }, {
        "name": "低標",
        "startTime": 0,
        "endTime": obj_experience_score[rank3].experience_score,
        "color": "#04D215"
    }],
    "valueAxes": [{
        "axisAlpha": 0,
        "gridAlpha": 0.1
    }],
    "startDuration": 1,
    "graphs": [{
        "balloonText": "<b>[[category]]</b><br>starts at [[startTime]]<br>ends at [[endTime]]",
        "colorField": "color",
        "fillAlphas": 0.8,
        "lineAlpha": 0,
        "openField": "startTime",
        "type": "column",
        "valueField": "endTime"
    }],
    "rotate": true,
    "columnWidth": 1,
    "categoryField": "name",
    "categoryAxis": {
        "gridPosition": "start",
        "axisAlpha": 0,
        "gridAlpha": 0.1,
        "position": "left"
    },
    "export": {
    	"enabled": true
     }
});


var chart = AmCharts.makeChart("TraitChartdiv", {
	"theme": "light",
    "type": "serial",
     "dataProvider": [{
        "name": obj[0].UserName,
        "startTime": obj[0].trait_score,
        "endTime": 0,
        "color": "#FF0F00"
    }, {
        "name": "高標",
        "startTime":  obj_trait_score[0].trait_score,
        "endTime":  obj_trait_score[rank1].trait_score,
        "color": "#FF9E01"
    }, {
        "name": "均標",
        "startTime": obj_trait_score[rank3].trait_score,
        "endTime": obj_trait_score[rank1].trait_score,
        "color": "#F8FF01"
    }, {
        "name": "低標",
        "startTime": 0,
        "endTime": obj_trait_score[rank3].trait_score,
        "color": "#04D215"
    }],
    "valueAxes": [{
        "axisAlpha": 0,
        "gridAlpha": 0.1
    }],
    "startDuration": 1,
    "graphs": [{
        "balloonText": "<b>[[category]]</b><br>starts at [[startTime]]<br>ends at [[endTime]]",
        "colorField": "color",
        "fillAlphas": 0.8,
        "lineAlpha": 0,
        "openField": "startTime",
        "type": "column",
        "valueField": "endTime"
    }],
    "rotate": true,
    "columnWidth": 1,
    "categoryField": "name",
    "categoryAxis": {
        "gridPosition": "start",
        "axisAlpha": 0,
        "gridAlpha": 0.1,
        "position": "left"
    },
    "export": {
    	"enabled": true
     }
});
    setInterval( randomValue, 2000 );
// set random value
function randomValue() {
  if ( gaugeChart ) {
    if ( gaugeChart.arrows ) {
      if ( gaugeChart.arrows[ 0 ] ) {
        if ( gaugeChart.arrows[ 0 ].setValue ) {
          gaugeChart.arrows[ 0 ].setValue( score );
          gaugeChart.axes[ 0 ].setBottomText(obj[0].Text);
        }
      }
    }
  }
}

</script>


