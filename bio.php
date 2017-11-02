<?php

	// autoloader
	spl_autoload_register(function($class) {
		require_once 'classes/' . $class . '.php';
	});

	define ('TITLE', 'Biografija');
	define ('KEYWORDS', 'Zemljotres, biografija, članovi');
	define ('DESCRIPTION', 'Biografija benda');
	require_once 'templates/header.php';
?>
    	<h1>Biografija</h1>

    	<section class="bio">

    		<div class="members">   			
    			<?php
					$membersRepository = new MembersRepository;
					$members = $membersRepository -> getAll();
					foreach ($members as $member) { ?>

						<div class="member">
							<img class="logo" src="images/<?php print $member -> photo ?>">
							<p class="member-name"><?php print $member -> first . " " . $member -> last ?></p>
							<p class="member-instrument"><?php print $member -> instrument ?></p>
							<p class="member-bio"><?php print $member -> bio ?></p>
						</div>
				<?php } ?>
    		</div>
    		
    		<p>Vokalno instrumentalni sastav Zemljotres je nastao 20.07.2008. u Aradcu. Grupa svira pretežno rock and roll sa elementima blues, country i progresiv zvuka. Ideja o Zemljotresu je nastala 2008. godine, da bi iste godine u maju pocelo oformljavanje sastava od strane Janka Hrubika (gitara) i Jana Litavskog (pevač), a u julu su se pridružili i Ivan Hrubik (bubnjevi) i Vladimir Bagljaš (bas). Ivan Hrubik nije mogao da dođe u bend ranije jer se čekalo da poraste (mada se to i dalje čeka). Veliki uticaj na grupu imali su Atomsko Sklonište, Riblja Čorba, Psihomodo Pop, Buldožer, AC/DC, Rolling Stones i drugi, te je prvi repertoar bio sastavljen od obrada pesama pomenutih grupa.</p>
			<p>Prvu svirku su imali u oktobru u Belom Blatu gde su dobili sjajnu podršku od publike te im je to bio vetar u leđa. Od samog početka nastupali su u neuobičajenim kostimima, koristeći razne rekvizite čime su svaku svirku pretvarali u neobičan performans. U narednom periodu Zemljotres je tresao bine i po moto skupovima i ostalim velikim manifestacijama, a time su stekli reputaciju benda koji prati moto susrete. Učestvovali su i na takmičenjima Radija 202 i Live shot Kanala 9 na kojima su stigli do finala.</p>
			<p>Početkom 2009. Zemljotresu se pridružio i Mihal Labat (klavijature). Još pre dolaska Labata u grupu, imali su osnovu za prvi album. Tokom 2009. navežbali su sve pesme sa prvog albuma i promovisali ih po svirkama. One su odlično prihvaćene, što je bila dodatna motivacija da se nastavi sa autorskim radom. Početkom 2010.godine Litavski napušta grupu, a Labat preuzima ulogu pevača. Pored mnogobrojnih svirki tokom 2011. godine snimaju demo album "Šest stepeni Rihterove skale". Uz mnogobrojne nastupe u 2012. godini, pripremali su se za snimanje prvog albuma.</p>
			<p>Početkom 2013. godine na predlog Petra Jelića odlaze u studio i snimaju prvi studijski album "Šest stepeni Rihterove skale". Nekoliko meseci kasnije zbog bračnih obaveza grupu napušta Mihal Labat, a na njegovo mesto dolazi gitarista i pevač Danijel Karabenč, koji je nastupao nekoliko puta sa Zemljotresom kao pridruženi član. Tom promenom grupa dobija tvrđi zvuk, jer je ulogu klavijature preuzela gitara. U tom sastavu grupa je nastavila sa nastupima na moto skupovima, klubovima i festivalima od kojih se izdvaja Farba fest, na kojem su osvojili drugo mesto, a gostovali su i na nekoliko televizijskih emisija. Početkom 2014. godine je usledila promena članova u grupi i na mesto Ivana Hrubika i Vladimira Bagljaša dolaze bubnjar Veselin Grcić i basista Petar Paštrović, koji su do danas ostali aktivni članovi.</p>
			<p>Uporedo sa daljim radom na autorskim pesmama, grupa nastavlja sa intenzivnim svirkama, od kojih se izdvaja nastup sa YU Grupom. Ubrzo nakon toga dobijaju ponudu i za nekoliko nastupa u Slovačkoj, čime su našli put do nove publike i dokazali da jezička barijera ne predstavlja problem. Na tim nastuima su se čule i neke autorske pesme prepevane na slovački jezik. Nakon dolaska iz Slovačke ulogu pevača preuzima gitarista Janko Hrubik i tada grupa počinje sa intenzivnim radom na drugom albumu. Početkom 2015. godine u okviru evropske turneje poznate slovačke punk-rock grupe Ine Kafe, Zemljotres na dva koncerta u Bačkom Petrovcu i Beogradu nastua kao predgrupa zajedno sa grupom Zbogom Brus Li. Nakon toga Danijel Karabenč napušta Zemljotres i od tada grupa nastupa u tročlanom sastavu. Kao specijalni gost na jednom od nastupa u Zrenjaninu grupi se pridružila lokalna legenda Panta Šiklja Nafta sa kojim su izveli nekoliko njegovih hitova na opšte oduševljenje publike.</p>
			<p>Krajem 2015. godine ponovili su mini turneju po Slovačkoj i napravili sledeći korak u radu na autorskom materijalu. Grupa odlazi u studio i započinje snimanje pesama za album "Sokrat ponovo jaše" koji bi trebalo da ugleda svetlost dana krajem septembra 2016. godine. U junu se grupi pridružio i Denis Grubić na gitari kao pojačanje za predstojeće koncerte.</p>

    	</section>
		
<?php
	require_once 'templates/footer.php';
?>