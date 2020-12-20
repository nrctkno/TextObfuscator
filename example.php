<?php
require_once 'TextObfuscator.php';
require_once 'RequestFacade.php';

$rq = new RequestFacade();

if ($rq->isPost()) {
    $input = $rq->getParam('input');
    $terms = $rq->getParam('terms');
    $naming = $rq->getParam('naming');

    $word_process = $rq->getParam('word_process');
    $word_except = $rq->getParam('word_except');
    $word_naming = $rq->getParam('word_naming');

    $email_process = $rq->getParam('email_process');
    $email_naming = $rq->getParam('email_naming');
    $output = $input;

    if (!empty($email_process)) {
        $output = TextObfuscator::obfuscateRegex($output, '/[^@\s]*@[^@\s]*\.[^@\s]*/', '(email)');
    }
    if (!empty($terms)) {
        $strings_to_obfuscate = TextObfuscator::getTerms($terms);
        $output = TextObfuscator::obfuscateStrings($output, $strings_to_obfuscate, $naming);

        if (!empty($word_process)) {
            $strings_to_obfuscate = TextObfuscator::getWords($terms, $word_except);
            $output = TextObfuscator::obfuscateStrings($output, $strings_to_obfuscate, $word_naming);
        }
    }
} else {
    $input = "";
    $output = "";
    $terms = "John Robinson\nPeter Gabriel\nMichael Jacksonville";
    $naming = "user_{id}";
    $word_except = 'a e i o u the an any';
    $word_naming = 'name_{hash}';
    $email_naming = '(email)';
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <style>
            textarea,input {font-family: "courier"; font-size: 14px;} 
            textarea {width:100%; min-width:100%; max-width:100%; height: 200px; white-space: pre; overflow-wrap: normal; overflow-x: scroll;}
            label{font-weight: bold; display: block;}
            .row{ padding:5px;} .col{display: inline-block; vertical-align:top;} .col50{width:48%;} .col100{width:96%;} .text-center{text-align: center;}
        </style>
    </head>
    <body>
        <h1>Obfuscate text</h1>
        <form method="POST" action="">
            <div class="row">
                <div class="col col100">
                    <label>Input</label>
                    <textarea name="input"><?php echo $input ?></textarea>
                </div>
            </div>

            <hr />

            <div class="row">
                <div class="col col50">
                    <label>Terms to obfuscate (one per line)</label>
                    <textarea name="terms"><?php echo $terms ?></textarea>
                    <br /><br />
                    Naming structure (use {id} and {hash}): 
                    <input name="naming" value="<?php echo $naming ?>">
                    <br /><br />

                    <input type="checkbox" name="word_process" <?php echo empty($word_process) ? '' : 'checked=""' ?>">Also replace every word, 
                    except: <input name="word_except" value="<?php echo $word_except ?>"> 
                    and rename them to (use {id} and {hash}): <input name="word_naming" value="<?php echo $word_naming ?>">
                </div>
                <div class="col col50">
                    <label>Formatted data</label>
                    <input type="checkbox" name="email_process">Obfuscate emails
                    and rename them to: <input name="email_naming" value="<?php echo $email_naming ?>">
                </div>
            </div>
            <div class="row text-center">
                <div class="col col100">          
                    <button type="submit">Obfuscate</button>
                </div>
            </div>
        </form>

        <hr />

        <div class="row">
            <div class="col col100">
                <label>Output</label>
                <textarea name="output"><?php echo $output ?></textarea>
            </div>
        </div>
    </body>
</html>