<?php 
if(!isset($_SESSION)){
   session_start();
}
//require_once '../inc/ini.php';

if(!isset($_SESSION['c_uti_login']) )
	{
		//header('Location: ../index.php?mes_error=id');
		require("identification.php");
		die();
	}

?>
<html>
<head>
	<title>IEO Droma - Consultation dictionnaire en ligne moutier</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta name="keywords" lang="fr" content="occitan, langue d'oc, lenga d'òc, droma, drôme, patois, vivaroalpin, vivaroaupenc, provençal, provençau, prouvençau, IEO, Institut d'Etudes Occitanes, Institut d'Estudis Occitan, langues régionales, dictionnaire, moutier">
	<meta name="description" content="Consultation internet du dictionnaire Moutier">
	<meta http-equiv="Content-Language" content="fr">
	<meta name="Identifier-url" content="http://www.ieo-droma.org/" />
		<meta name="Robots" content="Index, Follow" />
		<meta name="Revisit-After" content="14 days" />
	<meta name="Author" content="IEO Droma" />
	<meta name="Owner" content="IEO Droma" />
	<link rel="shortcut icon" href="http://www.ieo-droma.org/ieo.ico" />
	<link type="text/css" rel="stylesheet" href="../skins/2015_ieo02/style.css" />
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			var search_timeout = undefined;

			$("#recherche").bind('keyup', function() {
														$("#js").val("yes");
														if (search_timeout != undefined) 
															{ clearTimeout(search_timeout); }
														search_timeout = setTimeout(function() 
															{ search_timeout = undefined;
															  loadlist(); }
															, 700);
													  });
	
			$("#graphie").click(function(){
											$("#js").val("yes");
											loadlist();
										  });
	
			$("#graphie2").click(function(){
											$("#js").val("yes");
											loadlist();
										   });
	
			$("#btnok").click(function(){
											$("#js").val("no");
										});

			});
	
		function loadlist()
		{
			$("#loading").empty();
			$("#loading").append("Chargement...");
			$.post("backend.php",
					{
						graphie: $("input[@type=radio][@checked]").val(),
						alphabet: $("#recherche").val(),
						action: "liste"
					},
					function(xml) 
					{
						listemots(xml);
					}
				   );
			return false;
		}
	
		function loadlistlimit(limit)
		{
			$("#loading").empty();
			$("#loading").append("Chargement...");
			$.post("backend.php",
					{
						graphie: $("input[@type=radio][@checked]").val(),
						alphabet: $("#recherche").val(),
						action: "liste",
						limitdebut: limit
					},
					function(xml) 
					{
						listemots(xml);
					}
				   );
			return false;
		}
	
		function listemots(xml) {
			$("#loading").empty();
			$("#listedemots").empty();
			$("#enregistrement").empty();
			var intNbEnregistrement = $("nbenregistrement",xml).text();
			var intLimite = $("limite",xml).text();
			$("#enregistrement").append($("nbenregistrement",xml).text()+" enregistrements trouvés");

			if($("status",xml).text() == "0") return;
			$("liste",xml).each(function(id) {
				liste = $("liste",xml).get(id);
				$("#listedemots").append("<a href=\"javascript:;\" onclick=\"loaddefinition('"+$("idmot",liste).text()+"');\" >"+$("mot",liste).text()+"<\/a><br />");
			});
		
			if(parseInt(intLimite) <= parseInt(intNbEnregistrement))
			{
				$("#listedemots").append("<a href=\"javascript:;\" onclick=\"loadlistlimit("+$("limite",xml).text()+");\">suite ...<\/a><br />");
			}
		
			if(parseInt(intLimite) >= 201)
			{
				var intPrecedent = parseInt(intLimite) - 400;
				$("#listedemots").prepend("<a href=\"javascript:;\" onclick=\"loadlistlimit("+intPrecedent+");\">... precedent<\/a><br />");
			}
		}
	
		function loaddefinition(idmot)
		{
			$.post("backend.php",
						{
						action: "definition",
						graphie: $("input[@type=radio][@checked]").val(),
						idmot: idmot
						},
						function(xml)
						{
						definition(xml);
						}
		           );
			return false;
		}
	
		function definition(xml) 
		{
			$("#description").empty();
			$("#description").append($("response",xml).text());
		}
	
	</script>
	<style>
		body 		{
						text-align:center;
						font-family: Verdana;
					}
		#page_dico	{
						text-align:left;
						width:700px;
						margin:auto;
					}
		#listing 	{
						width:200px; 
						height:349px; 
						overflow: auto; 
						float:left; 
						background-color: #CCCCFF; 
						layer-background-color: #CCCCFF; 
						border: 1px none #000000;
					}

		@media print 	{
				#listing 	{
							display:none;
							}
						}

		#description 	{
						width:450px; 
						height:349px; 
						overflow: auto; 
						float:left; 
						background-color: #FFFFFF; 
						layer-background-color: #FFFFFF; 
						border: 1px solid #000000;
						}

		#description p	{
						margin:0;
						padding:2px;
						}

		#description br	{
						line-height:3px;
						}

		@media print 	{
			#description 	{
							/*text-align:center;*/
							background-color: #FFFFFF; 
							color: #000000;
							overflow: hidden; 
							width:100%; 
							height:100%; 
							float:none;
							border:0px;
							}
						}

		form 			{
						width:650px;
						background-color: #DDDDDD; 
						border: 1px solid #000000;
						font-size:1em;
						}

		@media print 	{
			form 			{
							display:none;
							}
						}

		#enregistrement	{
							font-style:italic;
						}

		#legende 		{
							clear:both;
							width:650px;
						}

		.ecran 			{
						text-align: center;
						}
		@media print 	{
			.ecran 			{
							display:none;
							}
						}

		@media print 	{
			.imprimer 		{
							/*display:none;*/
							visibility:hidden;
							height:20px;
							}
						}
	</style>
</head>

<div id='headTop'></div>
<body>
<div id="page_dico">
<h1 style="text-align: center;"> Dictionnaire des dialectes dauphinois interactif d'apr&egrave;s l'Abb&eacute; Louis Moutier</h1>
<?php

/*
echo("<br> post vaut : <pre>");
print_r($_POST);
echo("</pre>");
echo("<br> Get vaut : <pre>");
print_r($_GET);
echo("</pre>");
*/

// jcp - 21dec2015 - condition ajoutée pour initialisation variable
if (!isset($graphie)) {	$graphie = "auteur"; }
// jcp - 21dec2015 - condition ajoutée pour initialisation variable
if (!isset($js)) { $js = "no"; }

// jcp - ajout de la condition d'entrée sur $_POST et $_GET pour éviter un warning sur l'index "jsj" quand celui-ci n'est pas encore défini
if (isset($_POST["action"]) || isset($_GET["def"])) 
{
if (isset($_POST["action"]) || isset($_GET["def"]) || ($_POST["js"] == 'no') || ($_SESSION["js"] == 'no'))
{

//--configuracion jcp


$dbhost = "10.0.206.77";
$dbuser = "ieodroma";
$dbpass = "ieodroma2403";
$dbname = "ieodroma";

/*
$dbhost = "sql.free.fr";
$dbuser = "dromaoccitana";
$dbpass = "mistrau";
$dbname = "dromaoccitana";
*/
/*
$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "ieodroma";
*/
	
	// Script
	error_reporting(E_ALL);
	
    // jcp - 21dec2015 - old formulation - dbconn = mysql_connect($dbhost,$dbuser,$dbpass);
	// jcp - 21dec2015 - old formulation - mysql_select_db($dbname,$dbconn);
	$dbconn = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);

	$limitdebut = '';
	$limitelongueur = '';
	if (isset($_POST["action"]))
	{
		$graphie = $_POST["graphie"];
		$alphabet = $_POST["alphabet"];
		$action = $_POST["action"];
		$limitdebut = $_POST["limitdebut"];
		$js = $_POST["js"];
	}
	else 
	{
		$graphie = $_SESSION["graphie"];
		$alphabet = $_SESSION["alphabet"];
		$action = $_SESSION["action"];
		$limitdebut = $_SESSION["limitdebut"];
		$js = $_SESSION["js"];
	}
	//$mot = $_POST[""];
	
	// *** liste les mots ***
	// *** si action = liste ***
	
	if ($limitdebut == '')
	$limitdebut = 0;
	if ($limitelongueur == '')
	$limitelongueur = 200;
	
	
	if (@$action == 'liste')
	{
		if (!isset($alphabet)) $alphabet="a";
		if(@$graphie == "auteur")
		{
			$query_graphie = "SELECT SQL_CALC_FOUND_ROWS mot_vedette_epuree, IDmot_occitan";
			$query_graphie .= " FROM gl_mot_occitan";
			$query_graphie .= " WHERE lettre='" . $alphabet{0} . "' AND mot_vedette_epuree LIKE '" . $alphabet . "%'";
			$query_graphie .= " ORDER BY mot_vedette_epuree ASC LIMIT $limitdebut,$limitelongueur";
		}
		else 
		{
			$query_graphie = "SELECT SQL_CALC_FOUND_ROWS graphie_commune_epuree, IDmot_occitan";
			$query_graphie .= " FROM gl_mot_occitan";
			$query_graphie .= " WHERE lettre='" . $alphabet{0} . "' AND graphie_commune_epuree LIKE '" . $alphabet . "%'";
			$query_graphie .= " ORDER BY graphie_commune_epuree ASC LIMIT $limitdebut,$limitelongueur";
		}
		
		// jcp - $ListeMots = mysql_query($query_graphie);
		// jcp - $querynbmots = mysql_query("SELECT FOUND_ROWS()");
		// jcp - $nbmots = mysql_fetch_row($querynbmots);
		$ListeMots = mysqli_query($dbconn,$query_graphie);
		$querynbmots = mysqli_query($dbconn,"SELECT FOUND_ROWS()");
		$nbmots = mysqli_fetch_row($querynbmots);
		$nbenregistrement = $nbmots[0];
		
		if($nbenregistrement == 0) $status_code = 0;
		else $status_code = 1;
		
		$limitecalc = $limitdebut + 200;
		
		if($status_code == 1)
		{
			$i = 0;
			// jcp - while($Mot = mysql_fetch_array($ListeMots))
			while($Mot = mysqli_fetch_array($ListeMots))
			{
				$listedeMots[$i] =  $Mot[0];
				$listedeIdMots[$i]= $Mot[1];
				$i++;
			}
		}
		
		// init des variables de sessions
		$_SESSION["graphie"] = $graphie;
		$_SESSION["alphabet"] = $alphabet;
		$_SESSION["action"] = $action;
		$_SESSION["limitdebut"] = $limitdebut;
		$_SESSION["js"] = $js;
		
	}
	
	//echo("<br> test sit get def existe");
	//echo("<br> le get def vaut : " . $_GET["def"]);
	
	
	if (isset($_GET["def"]))
	{
		//$mot = $_GET["def"];
		$idmot=$_GET["def"];
	    
		if(@$graphie == "auteur")
		{
			$mot_epuree = "mot_vedette_epuree";
		}
		else 
		{
			$mot_epuree = "graphie_commune_epuree";
		}
		
		$requete_mot_clef="SELECT $mot_epuree FROM gl_mot_occitan WHERE IDmot_occitan='$idmot'";
		//echo("<br> requete_mot_clef vaut $requete_mot_clef");
		
		// jcp - $resultat_mot_clef = mysql_query($requete_mot_clef);
		// jcp - $info_mot=mysql_fetch_object($resultat_mot_clef);
		$resultat_mot_clef = mysqli_query($dbconn, $requete_mot_clef);
		$info_mot=mysqli_fetch_object($resultat_mot_clef);
		$mot=$info_mot->$mot_epuree;
		
		//echo("<br> mot obtenu vaut $mot");
		
		//$definition = mysql_fetch_row($query_resultat_edition);
		
		$requete_resultat_edition = "SELECT resultat_edition";
		$requete_resultat_edition .= " FROM gl_mot_occitan";
		$requete_resultat_edition .= " WHERE IDmot_occitan = '".$idmot."'";
		
		//echo("<br> requete_resultat_edition vaut $requete_resultat_edition");
		
		/*
		$requete_resultat_edition = "SELECT resultat_edition";
		$requete_resultat_edition .= " FROM gl_mot_occitan";
		$requete_resultat_edition .= " WHERE lettre = '".$mot{0}."'";
		$requete_resultat_edition .= " AND ".$mot_epuree." = '".$mot."'";
		*/
		
		$requete_liste_mot_localite = "SELECT DISTINCT(c.".$mot_epuree.") AS mot_vedette,"; 
		$requete_liste_mot_localite .= " b.mot_oc_local AS mot_occitan_local, b.code_localisation AS localisation, b.grammaire_locale AS grammaire_locale, b.phonétique AS phonétique,";
		$requete_liste_mot_localite .= " d.categ_grammaire AS grammaire, d.precision_grammaire AS precision_grammaire";
		$requete_liste_mot_localite .= " FROM gl_mot_local b, gl_mot_occitan c, gl_mot_oc_generique d";
		$requete_liste_mot_localite .= " WHERE b.lettre = '".$mot{0}."' AND c.lettre = '".$mot{0}."' AND d.lettre = '".$mot{0}."'"; 
		$requete_liste_mot_localite .= " AND c.".$mot_epuree." = '".$mot."'";
		$requete_liste_mot_localite .= " AND c.no_mot_occitan = b.code_mot_occitan";
		$requete_liste_mot_localite .= " AND c.no_mot_occitan = d.code_mot_occitan";
		
		$requete_definition = "SELECT  e.mot_equiv_francais AS traduction, e.explication_sens AS sens,";
		$requete_definition .= " f.mot_expl_syno_fr AS definition";
		$requete_definition .= " FROM gl_mot_occitan c, gl_mot_oc_generique d, gl_traduction_sens e, gl_synonym_fr f";
		$requete_definition .= " WHERE c.lettre = '".$mot{0}."' AND d.lettre = '".$mot{0}."' AND e.lettre = '".$mot{0}."' AND f.lettre = '".$mot{0}."'";
		$requete_definition .= " AND c.".$mot_epuree." = '".$mot."'";
		$requete_definition .= " AND c.no_mot_occitan = d.code_mot_occitan";
		$requete_definition .= " AND d.no_mot_generique = e.code_mot_gen_oc";
		$requete_definition .= " AND e.no_traduction = f.no_mot_equiv_fr";
		
		$requete_citation = "SELECT g.nom_auteur AS auteur, g.oeuvre AS oeuvre, g.phrase AS phrase";
		$requete_citation .= " FROM gl_mot_occitan c, gl_mot_oc_generique d, gl_traduction_sens e, gl_citations g";
		$requete_citation .= " WHERE c.lettre = '".$mot{0}."' AND d.lettre = '".$mot{0}."' AND e.lettre = '".$mot{0}."' AND g.lettre = '".$mot{0}."'";
		$requete_citation .= " AND c.".$mot_epuree." = '".$mot."'";
		$requete_citation .= " AND c.no_mot_occitan = d.code_mot_occitan";
		$requete_citation .= " AND d.no_mot_generique = e.code_mot_gen_oc";
		$requete_citation .= " AND e.no_traduction = g.no_mot_fran";
		
		
		// *** utilisation de la requete requete_resultat_edition
		// jcp - $query_resultat_edition = mysql_query($requete_resultat_edition);
		// jcp - $definition = mysql_fetch_row($query_resultat_edition);
		$query_resultat_edition = mysqli_query($dbconn,$requete_resultat_edition);
		$definition = mysqli_fetch_row($query_resultat_edition);
		
		
		/*
		echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\" ?>\n";
		echo "<response>\n";
		echo $definition[0];
		echo "</response>";
		*/
	}
}
} 
// jcp

?>

<form id="formrecherche" name="form1" method="post" action="index.php" >
  <p>
  <b>Rechercher :</b> &nbsp;  <span id="enregistrement"></span>
  </p>
  <p> 
  <input type="hidden" name="action" value="liste" >
  <input type="hidden" name="limitdebut" value="0" >
  <input type="hidden" name="js" value="no" id="js">
  <input type="radio" name="graphie" value="auteur" id="graphie" <?php if (!isset($graphie)) echo "checked"; else if ($graphie == "auteur") echo "checked";?> >
     &nbsp;Graphie de l'auteur
     &nbsp;&nbsp; <input type="radio" name="graphie" value="classique" id="graphie2" <?php if ($graphie == "classique") echo "checked";?>>
    &nbsp;Graphie Classique
   &nbsp;&nbsp; Mot : <input type="text" name="alphabet" id="recherche" size="15" <?php if (($js == 'no') && (isset($alphabet))) echo "value='" . $alphabet . "'"; ?>>
    <input type="submit" name="Submit" value="OK" id="btnok" >
    <span id="loading"></span>
  </p>
</form>
<div id="listing"> 
	<p id="listedemots">
	<?php
	//foreach ($listedeMots as $i)
	// jcp - ajout condition pour éviter warning quand $listedeMots pas encore défini
	if (isset($listedeMots)) {
	if (is_array($listedeMots)) 
		{
		foreach ($listedeMots as $clef=>$valeur)
			{
			$i=$valeur;
			$idmot=$listedeIdMots[$clef];
			//echo "<a href=\"index.php?def=". $i ."\">". $i . "</a><br>";
			//echo "<a href=\"consultation2_dico.php?def=". $idmot."\">". $i . "</a><br>";
			echo "<a href=\"index.php?def=". $idmot."\">". $i . "</a><br>";
			}
		}
	else {}	
	}
	?>
	</p>
</div>
<div id="description"> 
  <?php 
  if (isset($definition[0])) {
	  echo $definition[0];
	  }  
  ?>
</div>
<div id="legende">
	<p align="right" class="imprimer"><a href="javascript:window.print()"><img src="/img/imprimer.png" alt="Imprimer" border="0"> Imprimer</a></p>
	<table>
	<tr>
	<td colspan="2">
	<p class="imprimer"> 
	<i>
	NB : la phonétique utilise la police Ipakiel, si vous ne l'avez pas sur votre PC vous pouvez la télécharger en cliquant sur ce lien : 
	<a href="fonts/index.htm" target="_blank">telecharger font Ipakiel</a> 
	</i>
	</p>
	</td>
	</tr>
	</table>
	<p>Légende &nbsp;</p>
	
	<ul class="imprimer">
		<li><a href="/dictionnaire/doc_dico/liste_localites.htm" target="_blank">Liste des localités</a></li>
		<li><a href="/dictionnaire/doc_dico/liste_abreviations_conventionnels.htm" target="_blank">Liste des abréviations conventionnelles</a></li>
		<li><a href="/dictionnaire/doc_dico/phonetiques.pdf" target="_blank">Signes phonétiques utilisés (doc pdf)</a></li>
	</ul>
	
	<div style="width:620px">
		<div style="width:290px; float:left; padding-left:20px">
			<p>Lexique Grammaire </p>
			
			<ul> 
			<li> s.f. : Singulier féminin </li>
			<li>adj : Adjectif </li>
			<li>adv : Adverbe </li>
			<li>loc. adv : Locution adverbiale </li>
			<li>m.f. : même forme  </li>
			<li>n.f. : non formulé  </li>
			<li>part. adj : Participe adjectival  </li>
			<li>s. et adj : Substantif et adjectif  </li>
			<li>s.f : Substantif féminin  </li>
			<li>s.m : Substantif masculin  </li>
			<li>v.a : Verbe actif  </li>
			<li>v.n : Verbe neutre  </li>
			<li>v.r : Verbe réflechi  </li>
			<li>v.tr : Verbe transitif  </li>
			</ul>
		</div>
		<div style="width:290px; float:right; padding-left:20px">
			<p>Codes des localit&eacute;s abr&eacute;g&eacute;s</p>
			
			<ul> 
				<li>A (parfois AD) = Alpes dauphinoises </li>
				<li>B = Bas Dauphiné</li>
				<li>CH = Charpey </li>
				<li>DI = Diois</li>
				<li>H = Haut Dauphiné</li>
				<li>M = Moyen Dauphiné</li>
				<li>TF = Terres Froides</li>
				<li>TR = Trièves</li>
				<li>TRIC = Tricastin </li>
				<li>V = vieux</li>
			</ul>
		</div>
	</div>

</div>
</div>
</body>
</html>
