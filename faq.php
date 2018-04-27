<?php $pageTitle = "FAQ"; include("includes/header.inc.php"); ?>
	




    <body style="background-color: #e5e5e5">
        <section class="faq-slider text-center">
            <h1>FAQ</h1>
            <p class="lead">
                in page of FAQ you find most and popular question from users to understand this website
            </p>
        </section>
        <section class="main-reside">
            <div class="container">
                <div class="faq-statics">
                    <h3 class="specail-head">Our Statics</h3>
                    



                    <canvas id="canvas"></canvas>
                


                </div>
                <section class="ploto-question">
                    <div class="faq-data">
                        <h3 class="specail-head">Important Question</h3>
                        <p> were butterflies and liv'd but three summer days three such days with you I could fill with more delight than fifty<p>
                    </div>
                    <section class="faq-question">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <!--this is question-->
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="head-one">
                                    <h4 class="panel-title">
                                        <a class="collapsed" href="#collapse-one" data-parent="#accordion" data-toggle="collapse" aria-controls="collapse-one" aria-expended="true">
                                            why Freelancing website intersted in programming
                                        </a>
                                    </h4>
                                </div>
                                <div class="panel-collapse collapse" id="collapse-one" role="tabpanel" aria-labelledby="heading-one">
                                    <div class="panel-body">
                                        because programming and computer are technology of this area and no can instead it 
                                        and because i like this filed of engineering
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="head-two">
                                    <h4 class="panel-title">
                                        <a class="collapsed" href="#collapse-two" data-parent="#accordion" data-toggle="collapse" aria-controls="collapse-two" aria-expended="true">
                                            what are servises Freelancing website provide to me
                                        </a>
                                    </h4>
                                </div>
                                <div class="panel-collapse collapse" id="collapse-two" role="tabpanel" aria-labelledby="heading-two">
                                    <div class="panel-body">
                                        Freelancing provide ready designes to save time and provide space of memory equal 4 GB
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="head-three">
                                    <h4 class="panel-title">
                                        <a class="collapsed" href="#collapse-three" data-parent="#accordion" data-toggle="collapse" aria-controls="collapse-three" aria-expended="true">
                                            what the kind of filed in Freelancing website
                                        </a>
                                    </h4>
                                </div>
                                <div class="panel-collapse collapse" id="collapse-three" role="tabpanel" aria-labelledby="heading-three">
                                    <div class="panel-body">
                                        every this filed of Jobs like Graphic , web Deasigne , Developed ,Android , Ardunio , Adope products
                                        ,computing , Security , network
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="head-four">
                                    <h4 class="panel-title">
                                        <a class="collapsed" href="#collapse-four" data-parent="#accordion" data-toggle="collapse" aria-controls="collapse-four" aria-expended="true">
                                            when Freelancing website provide chat website and profile
                                        </a>
                                    </h4>
                                </div>
                                <div class="panel-collapse collapse" id="collapse-four" role="tabpanel" aria-labelledby="heading-four">
                                    <div class="panel-body">
                                        this is step is available alerady
                                    </div>
                                </div>
                            </div>
                            <!--end question-->
                        </div>
                    </section>
                </section>


































            </div>  
        </section>
        <section class="about-footer">
            <div class="container">
                <?php
                    $stat = new Statistics();
                    $numfreelancer = $stat->staticfreelancer(); 
                    echo "<input id='free' type='hidden' value='".$numfreelancer."'>";
                   
                    $numclient = $stat->staticclient(); 
                   echo "<input id='client' type='hidden' value='".$numclient."'>";


                    $numprop = $stat->staticproposal(); 
                    echo "<input id='prop' type='hidden' value='".$numprop."'>";


                    $numjob = $stat->staticjob(); 
                    echo "<input id='job' type='hidden' value='".$numjob."'>";


                    $numcontr = $stat->staticcontract(); 
                    echo "<input id='contr' type='hidden' value='".$numcontr."'>";


                    $numfeed = $stat->staticfeedback(); 
                    echo "<input id='feed' type='hidden' value='".$numfeed."'>";


                    $nummsg = $stat->staticmessage(); 
                    echo "<input id='msg' type='hidden' value='".$nummsg."'>";
                ?>
            </div>  
        </section>
        <script type="text/javascript" src="assets/js/canvas.js"></script>




<?php include("includes/footer.inc.php"); ?>