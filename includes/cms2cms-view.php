<?php
if (!defined('CMS2CMS_VERSION')) {
    die();
}

$dataProvider = new CmsPluginFunctionsHtml();
$dataProvider->logOut();

$key = $dataProvider->getOption('cms2cms-html-key');
$activated = $dataProvider->isActivated();
$targetUrl = $dataProvider->getSiteUrl();

$authentication = $dataProvider->getAuthData();

$ajaxNonce = $dataProvider->getFormTempKey('cms2cms-html-ajax-security-check');

$currentPluginUrl = plugin_dir_url(__FILE__);
$jsconig = file_get_contents($currentPluginUrl . '/config.txt');
$config = json_decode($jsconig, true);

$loader = new HtmlCmsBridgeLoader();
try {
    $loader->checkKey($key, $config['bridge']);
} catch (\Exception $exception) {
    $message = $exception->getMessage();
}

$fileStart = 'cms2cms-migration';

?>
<div class="wrap cms2cms-html-wrapper">
    <div class="cms2cms-html-container">
        <div class="cms2cms-html-header">
            <img src="<?php echo $currentPluginUrl; ?>/img/cms2cms-logo.png" alt="CMS2CMS Logo"
                 title="CMS2CMS - Migrate your website content to a new CMS or forum in a few easy steps"/>
        </div>
        <script language="JavaScript">
            var config = <?php echo $jsconig?>
        </script>
        <?php if ($activated) { ?>
            <script language="JavaScript">
                jQuery('.cms2cms-html-container').addClass('cms2cms_html_is_activated');
            </script>
            <div class="cms2cms-html-message">
                <span>
                    <?php echo sprintf(
                        $dataProvider->__('You are logged in CMS2CMS as %s', $fileStart),
                        $dataProvider->getOption('cms2cms-html-login')
                    ); ?>
                </span>
                <div class="cms2cms-html-logout">
                    <form action="" method="post" id="logout" data-logout="<?php echo $dataProvider->getLogOutUrl() ?>">
                        <input type="hidden" name="cms2cms_html_logout" value="1"/>
                        <input type="hidden" name="_wpnonce"
                               value="<?php echo $dataProvider->getFormTempKey('cms2cms_html_logout') ?>"/>
                        <button class="button-grey cms2cms-html-button" data-log-this="Logout" type="button">
                            <?php $dataProvider->_e('Logout', $fileStart); ?>
                        </button>
                    </form>
                </div>
            </div>
        <?php } ?>
        <div class="cms2cms-html-plugin">
            <div id="cms2cms_html_accordeon">
                <h3 class="step step-sign active">
                    <div class="step-numb-block"><i>1</i></div>
                    <b></b>
                    <h id="signIn"><?php $dataProvider->_e('Sign In', $fileStart); ?></h>
                    <h id="signUp" style="display: none"><?php $dataProvider->_e('Sign Up', $fileStart); ?></h>
                    <span class="spinner"></span>
                </h3>
                <?php
                $cms2cms_html_step_counter = 1;
                if (!$activated) { ?>
                    <div id="cms2cms_html_accordeon_item_id_<?php echo $cms2cms_html_step_counter++; ?>"
                         class="step-body cms2cms_html_accordeon_item cms2cms_html_accordeon_item_register">
                        <?php if (isset($message)) {?>
                            <div class="container-erorr-1">
                                <div class="block-erorr alert r-b-e">
                                    <?php $dataProvider->_e(sprintf('%s Please contact our', $message), $fileStart); ?>
                                    <a href="http://support.magneticone.com/visitor/index.php?/Default/LiveChat/Chat/Request/_sessionID=/_promptType=chat/_proactive=0/_filterDepartmentID=55/_randomNumber=bnkpattsb316qulj4o15lvdbkq6qsw53">
                                        <?php $dataProvider->_e('support team.', $fileStart); ?></a>
                                </div>
                            </div>
                        <?php } ?>
                        <form action="<?php echo $dataProvider->getLoginUrl() ?>"
                              callback="callback_auth"
                              validate="auth_check_password"
                              class="step_form"
                              id="cms2cms_html_form_register">
                            <div class="center-content">
                                <div class="error_message"></div>
                                <div class="user-name-block" style="display: none">
                                    <label
                                            for="cms2cms-html-user-name"><?php $dataProvider->_e('Full Name', $fileStart); ?></label>
                                    <input type="text" maxlength="50" id="cms2cms-html-user-name" name="name"
                                           value="" placeholder="Your name" class="regular-text"/>
                                    <div class="cms2cms-html-error name">
                                        <div class="error-arrow"></div>
                                        <span></span></div>
                                </div>
                                <div>
                                    <label
                                            for="cms2cms-html-user-email"><?php $dataProvider->_e('Email', $fileStart); ?></label>
                                    <input type="text" id="cms2cms-html-user-email" name="email"
                                           value="" placeholder="Your email" class="regular-text"/>
                                    <div class="cms2cms-html-error email">
                                        <div class="error-arrow"></div>
                                        <span></span></div>
                                </div>
                                <div>
                                    <label
                                            for="cms2cms-html-user-password"><?php $dataProvider->_e('Password', $fileStart); ?></label>
                                    <input type="password" id="cms2cms-html-user-password" name="password" value=""
                                           placeholder="Your password" class="regular-text"/>
                                    <div class="cms2cms-html-error password">
                                        <div class="error-arrow"></div>
                                        <span></span></div>
                                </div>

                                <input type="hidden" id="cms2cms-html-user-plugin" name="referrer"
                                       value="<?php echo $dataProvider->getPluginReferrerId(); ?>"
                                       class="regular-text"/>
                                <input type="hidden" id="cms2cms-html-site-url" name="siteUrl"
                                       value="<?php echo $targetUrl; ?>"/>
                                <input type="hidden" name="termsOfService" value="1">
                                <input type="hidden" id="loginUrl" name="login-url"
                                       value="<?php echo $dataProvider->getLoginUrl() ?>">
                                <input type="hidden" id="registerUrl" name="login-register"
                                       value="<?php echo $dataProvider->getRegisterUrl() ?>">
                                <button
                                        data-log-this="Authorization..."
                                        type="button" id="auth_submit" class="cms2cms-html-button button-green">
                                    <?php $dataProvider->_e('Continue', $fileStart); ?>
                                </button>
                                <a data-log-this="Forgot Password Link clicked"
                                   href="<?php echo $dataProvider->getForgotPasswordUrl() ?>"
                                   class="cms2cms-html-real-link" >
                                    <?php $dataProvider->_e('Forgot password', $fileStart); ?>
                                </a>
                                <p class="account-register"><?php $dataProvider->_e('Don\'t have an account yet?', $fileStart); ?>
                                    <a class="login-reg"><?php $dataProvider->_e('Register', $fileStart); ?></a></p>
                                <p class="account-login"><?php $dataProvider->_e('Already have an account?', $fileStart); ?>
                                    <a class="login-reg"><?php $dataProvider->_e('Login', $fileStart); ?></a></p>
                            </div>
                    </div>
                    <div>
                        </form>
                    </div>
                <?php } /* cms2cms_html_is_activated */ ?>
                <h3 class="step step-connect">
                    <div class="step-numb-block"><i>2</i></div>
                    <b></b>
                    <?php echo sprintf(
                        $dataProvider->__('Connect %s', $fileStart),
                        $dataProvider->getPluginSourceName()
                    ); ?>
                    <span class="spinner"></span>
                </h3>
                <div id="cms2cms_html_accordeon_item_id_<?php echo $cms2cms_html_step_counter++; ?>"
                     class="step-body cms2cms_html_accordeon_item">
                    <?php if (isset($message)) {?>
                        <div class="container-erorr-1">
                            <div class="block-erorr alert r-b-e">
                                <?php $dataProvider->_e(sprintf('%s Please contact our', $message), $fileStart); ?>
                                <a href="http://support.magneticone.com/visitor/index.php?/Default/LiveChat/Chat/Request/_sessionID=/_promptType=chat/_proactive=0/_filterDepartmentID=55/_randomNumber=bnkpattsb316qulj4o15lvdbkq6qsw53">
                                    <?php $dataProvider->_e('support team.', $fileStart); ?></a>
                            </div>
                        </div>
                    <?php } ?>
                    <form action="<?php echo $dataProvider->getWizardUrl(); ?>"
                          method="post"
                          id="cms2cms_html_form_estimate">
                        <div class="center-content">
                            <div class="error_message"></div>
                            <div id='error'></div>
                            <div>
                                <label
                                        for="sourceUrl"><?php echo $dataProvider->getPluginSourceName() ?> <?php $dataProvider->_e('website URL', $fileStart); ?> </label>
                                <input type="text" id="sourceUrl" name="sourceUrl" value="" class="regular-text"
                                       placeholder="<?php
                                       echo sprintf(
                                           $dataProvider->__('http://your_%s_website.com/', $fileStart),
                                           $dataProvider->getPluginSourceName()
                                       );
                                       ?>"/>
                                <div class="cms2cms-html-error">
                                    <div class="error-arrow"></div>
                                    <span></span></div>
                            </div>
                            <input type="hidden" name="platform-source"
                                   value="<?php echo $dataProvider->getPluginSourceType(); ?>"/>
                            <input type="hidden" id="targetUrl" name="targetUrl" value="<?php echo $targetUrl; ?>"/>
                            <input type="hidden" name="platform-target"
                                   value="<?php echo $dataProvider->getPluginTargetType(); ?>"/>
                            <input type="hidden" id="key" name="key"
                                   value="<?php echo $key; ?>"/>
                            <button id="verifySource_html"
                                    data-log-this="Verify Source Button pressed"
                                    type="button" name="verify-source" class="cms2cms-html-button button-green">
                                <?php $dataProvider->_e('Verify Connection', $fileStart); ?>
                            </button>
                        </div>
                    </form>
                    <div class="cms2cms-html-error"></div>
                </div>
            </div>
        </div> <!-- /plugin -->

        <!-- Support block -->
        <div class="support-block">
            <div class="cristine"></div>
            <div class="supp-bg supp-info supp-need-help">
                <div class="arrow-left"></div>
                <div class="need-help-blocks">
                    <div class="need-help-block">
                        <h3> <?php $dataProvider->_e('Need help?', $fileStart); ?></h3>
                        <?php $dataProvider->_e('Get all your questions answered!', $fileStart); ?><br>
                        <a href="http://support.magneticone.com/visitor/index.php?/Default/LiveChat/Chat/Request/_sessionID=/_promptType=chat/_proactive=0/_filterDepartmentID=55/_randomNumber=bnkpattsb316qulj4o15lvdbkq6qsw53"><?php $dataProvider->_e('Chat Now!', $fileStart); ?></a>
                    </div>
                    <div class="feed-back-block">
                        <h3> <?php $dataProvider->_e('Got Feedback?', $fileStart); ?></h3>
                        - <a
                                href="<?php echo $config['wp_feedback'] ?>"><?php $dataProvider->_e('Write a review', $fileStart); ?></a><br/>
                        - <?php $dataProvider->_e('Follow us on', $fileStart); ?> <a
                                href="<?php echo $config['twitter'] ?>"><?php $dataProvider->_e('Twitter', $fileStart); ?></a> <?php $dataProvider->_e('or', $fileStart); ?>
                        <a href="<?php echo $config['facebook'] ?>"><?php $dataProvider->_e('Facebook', $fileStart); ?></a>
                    </div>
                </div>
            </div>
            <div class="supp-bg supp-info packages-block">
                <?php $dataProvider->_e('Have specific data migration needs?', $fileStart); ?>
                <h3><?php $dataProvider->_e('Choose one of our', $fileStart); ?> <br/><a
                            href="<?php echo $config['public_host'] ?>/support-service-plans/"><?php $dataProvider->_e('Support Service Packages', $fileStart); ?></a>
                </h3>
            </div>
        </div>
        <div class="cms2cms-wix-footer cms2cms-wix-plugin position-m-block">
            <a href="<?php echo $config['public_host'] ?>/how-it-works/"><?php $dataProvider->_e('How It Works', $fileStart); ?></a>
            <a href="<?php echo $config['public_host'] ?>/faqs/"><?php $dataProvider->_e('FAQs', $fileStart); ?></a>
            <a href="<?php echo $config['public_host'] ?>/blog/"><?php $dataProvider->_e('Blog', $fileStart); ?></a>
            <a href="<?php echo $config['public_host'] ?>/terms-of-service/"><?php $dataProvider->_e('Terms', $fileStart); ?></a>
            <a href="<?php echo $config['public_host'] ?>/privacy-policy/"><?php $dataProvider->_e('Privacy', $fileStart); ?></a>
            <a href="<?php echo $config['ticket'] ?>"><?php $dataProvider->_e('Submit a Ticket', $fileStart); ?></a>
        </div>
        <!-- start Mixpanel -->
        <script type="text/javascript">
            (function (e, b) {
                if (!b.__SV) {
                    var a, f, i, g;
                    window.mixpanel = b;
                    a = e.createElement("script");
                    a.type = "text/javascript";
                    a.async = !0;
                    a.src = ("https:" === e.location.protocol ? "https:" : "http:") + '//cdn.mxpnl.com/libs/mixpanel-2.2.min.js';
                    f = e.getElementsByTagName("script")[0];
                    f.parentNode.insertBefore(a, f);
                    b._i = [];
                    b.init = function (a, e, d) {
                        function f(b, h) {
                            var a = h.split(".");
                            2 == a.length && (b = b[a[0]], h = a[1]);
                            b[h] = function () {
                                b.push([h].concat(Array.prototype.slice.call(arguments, 0)))
                            }
                        }

                        var c = b;
                        "undefined" !== typeof d ? c = b[d] = [] : d = "mixpanel";
                        c.people = c.people || [];
                        c.toString = function (b) {
                            var a = "mixpanel";
                            "mixpanel" !== d && (a += "." + d);
                            b || (a += " (stub)");
                            return a
                        };
                        c.people.toString = function () {
                            return c.toString(1) + ".people (stub)"
                        };
                        i = "disable track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.track_charge people.clear_charges people.delete_user".split(" ");
                        for (g = 0; g < i.length; g++)f(c, i[g]);
                        b._i.push([a, e, d])
                    };
                    b.__SV = 1.2
                }
            })(document, window.mixpanel || []);
            mixpanel.init("f48baf7f57bdb924fc68a786600d844e");
            mixpanel.identify("<?php echo md5($dataProvider->getUserEmail()); ?>");
        </script>
        <!-- end Mixpanel -->
        <div id="cms_overlay">
            <div class="circle-an">
                <span class="dot no1"></span>
                <span class="dot no2"></span>
                <span class="dot no3"></span>
                <span class="dot no4"></span>
                <span class="dot no5"></span>
                <span class="dot no6"></span>
                <span class="dot no7"></span>
                <span class="dot no8"></span>
                <span class="dot no9"></span>
                <span class="dot no10"></span>
            </div>
        </div>
    </div>
</div>