<?php

// print_r($post);

$title = $post->title();

$subtitle = $post->subtitle();

$total_score = $post->total_score();

$answer = $post->get_answer();

?>
<style>
.section {
    position: relative;
    padding: 94px 0 67px 0;
    /*display: block;*/
    border-bottom: 1px solid #d8d8d8;
}

.answer_queue {
    float: left;
    height: 150px;
    border: 1px solid #000;
}
</style>

<div class="row">

    <div class="col-sm-12" style=" text-align: center;">

        <h4>ABOUT YOU</h4>

        <section class="row section">

            <?php echo $answer['rmessage']; ?>

        </section>

        <h4>내게 맞는 온라인 쇼핑몰 창업을 위한 강의 콘텐츠</h4>

        <div class="row section" style=" text-align: center;">

            <div class="col-sm-12">

                <div class="answer_queue col-sm-4">
                    &npbs;
                </div>

                <div class="answer_queue col-sm-4">
                    &npbs;
                </div>

                <div class="answer_queue col-sm-4">
                    &npbs;
                </div>

            </div>

        </div>

    </div>

</div>
