<!DOCTYPE html>
<html>
<head>
	<title>ForceAnimes</title>
	<meta name="robots" content="noindex">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
	<script type="text/javascript" src="https://content.jwplatform.com/libraries/IDzF9Zmk.js"></script>
	<script type="text/javascript">jwplayer.key="64HPbvSQorQcd52B8XFuhMtEoitbvY/EXJmMBfKcXZQU2Rnn";</script>
	<style type="text/css" media="screen">html,body{padding:0;margin:0;height:100%}#apicodes-player{width:100%!important;height:100%!important;overflow:hidden;background-color:#000}</style>
</head>
<body>

<?php 
		error_reporting(0);
		
		$link = (isset($_GET['link'])) ? $_GET['link'] : '';

		$sub = (isset($_GET['sub'])) ? $_GET['sub'] : '';

		$poster = (isset($_GET['poster'])) ? $_GET['poster'] : '';
		
		if ($link != '') {
 			
 				include_once 'config.php';

				include_once 'curl.php';

				$curl = new cURL();

				$getSource = $curl->get($link);

				preg_match('/VIDEO_CONFIG\s*\=\s*\{(.*?)\]\}/', $getSource, $match);
				
				$deJson = json_decode('{' . $match[1] . ']}');
				
				foreach ($deJson->streams as $key => $value) {

					switch ($value->format_id) {
						case '37':
								$s[1080] = '{"file": "'.$value->play_url.'", "type": "video\/mp4", "label": "1080p"}';
							break;

						case '22':
								$s[720] = '{"file": "'.$value->play_url.'", "type": "video\/mp4", "label": "720p"}';
							break;
						
						case '18':
								$s[360] = '{"file": "'.$value->play_url.'", "type": "video\/mp4", "label": "360p"}';
							break;
					}

				}

				krsort($s);
				
				$enJson = implode(',', $s);
				
				$sources = '['.$enJson.']';
			
				$checkSource = preg_match('/\[\]/', $sources, $match);
				
				if($checkSource) {
					$sources = '[{"label":"undefined","type":"video\/mp4","file":"undefined"}]';
				}

				$result = '<div id="forceanimes-player"></div>';

				$data = 'var player = jwplayer("forceanimes-player");
							player.setup({
								sources: '.$sources.',
								aspectratio: "16:9",
								startparam: "start",
								primary: "html5",
								aboutlink:"forceanimes.xyz",
								abouttext:"ForceAnimes",
								autostart: false,
								playbackRateControls: [0.5, 0.75, 1.00, 1.50, 2.00],
								sharing: {heading: "Compartilhar"},
								skin: {active: "#FF0000"},
								preload: "auto",
								volume: 80,
								cast: {},
								logo: {
                                       file: "",
                                       logoBar: "",
                                       position: "top-right",
                                       link: ""
                                               },
								image: "https://i.imgur.com/2xAl3nA.png",
							    captions: {
							        color: "#FF0000",
							        fontSize: 16,
							        backgroundOpacity: 0,
							        fontfamily: "Helvetica",
							        edgeStyle: "raised"
							    },
							    tracks: [{ 
							        file: "'.$sub.'", 
							        label: "English",
							        kind: "captions",
							        "default": true 
							    }]
							});
							
		player.addButton(
   "https://i.imgur.com/0m33b0v.png",
   "Download video", 
   function() {  
    window.open(player.getPlaylistItem()["file"]+"?type=video/mp4&title=ForceAnimeQ-EPs-HD", "_blank").blur();
   //window.location.href = player.getPlaylistItem()["file"];
 },
"download"
);
player.addButton(
            "https://img.icons8.com/ios-filled/50/FFFFFF/double-right.png",
            "Pular OP/ED",
            function() {
                player.seek(player.getPosition() + 85)
            },
            "Pular OP/ED"
			
        );
				            player.on("setupError", function() {
				              swal("Server Error!", "Entre em contato conosco para corrigi-lo o mais rápido possível. Obrigado!", "error");
				            });
							player.on("error" , function(){
								swal("Server Error!", "Entre em contato conosco para corrigi-lo o mais rápido possível. Obrigado!", "error");
							});'
							;
							
				$packer = new Packer($data, 'Normal', true, false, true);

				$packed = $packer->pack();

				$result .= '<script type="text/javascript">' . $packed . '</script>';

			
				echo $result;

		} else echo 'Link not found!';
?>
</body>
</html>
