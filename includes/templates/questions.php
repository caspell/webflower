<?php

// print_r($post);

$qcount = $post->qcount;

$title = $post->title();

$subtitle = $post->subtitle();
?>

<div class="row">
    <div class="col-sm-12">
        <h3 class="text-center"><?php echo $title; ?><span class="st-point">.</span></h3>
<?php if (!empty($subtitle)) { ?>
        <h4 class="text-center"><?php echo $subtitle; ?></h4>
<?php } ?>
        <div class=""></div>

    </div>
</div>

<div class="row" id="question-box">
    <input type="hidden" id="qcount" value="<?php echo $qcount; ?>" />

<?php

// $imagepath = '/wp-content/plugins/webflower/includes/images/quiz_vs.png';
$questions = $post->questions();
for ($i = 0 ; $i < $qcount ; $i++) {
    $number = $i + 1;
    $qscore = $questions[$i]['qscore'];
    $q1 = $questions[$i]['q1'];
    $q2 = $questions[$i]['q2'];
?>

    <div class="col-sm-12 questions" style="<?php echo $i != 0 ? 'display:none':''; ?>">
        <div class="row">
          <h3 class="col-sm-12 text-center">
            <span class="step_num"><?php echo $number; ?></span> / <?php echo $qcount; ?>
          </h3>
        </div>

        <div class="questions-item">
            <input type="hidden" id="qscore" value="<?php echo $qscore; ?>" />
            <a class="question-item1" href="javascript:_void;">
                <div class="col-md-6 index_0"><h4 style="color:#fff;"><p style="color:#fff;"><?php echo $q1; ?></p> </h4></div>
        	</a>
            <a class="question-item2" href="javascript:_void();">
                <div class="col-md-6 index_1"><h4 style="color:#fff;"><p style="color:#fff;"><?php echo $q2; ?></p> </h4></div>
            </a>
            <div class="vs_mark"><img src="<?php echo $imagepath; ?>" alt=""></div>
        </div>
    </div>

<?php
}
?>
</div>

<script type="text/javascript">
function _void(){}
(function($){

    ({
        total_score:0
        , total_count:0
        , init:function(){

            var _self = this;

            _self.total_count = $("#qcount").val();

            _self.list = $("#question-box .questions");

            _self.list.each(function(index, item){
                var $item = $(this);
                var qscore = $item.find("#qscore").val();
                var isLast = index == _self.total_count - 1;

                $item.find(".question-item1").click(function(){
                    _self.choose(index, qscore, true, isLast);
                });

                $item.find(".question-item2").click(function(){
                    _self.choose(index, qscore, false, isLast);
                });

            });

            return _self;
        }
        , choose:function(index, score, is_ok, is_last){
            var _self = this;
            if ( is_ok ) _self.total_score += parseInt(score);
            if ( is_last ) _self.done();
            else {
                _self.list.eq(index).hide()
                .next().show();
            }
        }
        , result:function(){
            var _self = this;

            var rcount = parseInt('<?php echo $post->rcount(); ?>');
            $json = JSON.parse('<?php echo $post->resultsToJson(); ?>')

            for (i = 0 ; i < rcount ; i++){
                var $obj = $json[i];

                if (($obj.r1 <= _self.total_score) && (_self.total_score < $obj.r2)) {
                        location.href=$obj.rmessage;
                        break;
                }
            }

        }
        , done :function(){
            var _self = this;

            _self.result();
        }
    }).init();

})(jQuery);

</script>
