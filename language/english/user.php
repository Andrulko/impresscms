<?php
// $Id: user.php 2 2005-11-02 18:23:29Z skalpa $
//%%%%%%		File Name user.php 		%%%%%
define('_US_NOTREGISTERED','Not registered?  Click <a href="register.php">here</a>.');
define('_US_LOSTPASSWORD','Lost your Password?');
define('_US_NOPROBLEM','No problem. Simply enter the e-mail address we have on file for your account.');
define('_US_YOUREMAIL','Your Email: ');
define('_US_SENDPASSWORD','Send Password');
define('_US_LOGGEDOUT','You are now logged out');
define('_US_THANKYOUFORVISIT','Thank you for your visit to our site!');
define('_US_INCORRECTLOGIN','Incorrect Login!');
define('_US_LOGGINGU','Thank you for logging in, %s.');

// 2001-11-17 ADD
define('_US_NOACTTPADM','The selected user has been deactivated or has not been activated yet.<br />Please contact the administrator for details.');
define('_US_ACTKEYNOT','Activation key not correct!');
define('_US_ACONTACT','Selected account is already activated!');
define('_US_ACTLOGIN','Your account has been activated. Please login with the registered password.');
define('_US_NOPERMISS','Sorry, you dont have the permission to perform this action!');
define('_US_SURETODEL','Are you sure you want to delete your account?');
define('_US_REMOVEINFO','This will remove all your info from our database.');
define('_US_BEENDELED','Your account has been deleted.');
define('_US_REMEMBERME', 'Remember me');

//

//%%%%%%		File Name register.php 		%%%%%
define('_US_USERREG','User Registration');
define('_US_NICKNAME','Username');
define('_US_EMAIL','Email');
define('_US_ALLOWVIEWEMAIL','Allow other users to view my email address');
define('_US_WEBSITE','Website');
define('_US_TIMEZONE','Time Zone');
define('_US_AVATAR','Avatar');
define('_US_VERIFYPASS','Verify Password');
define('_US_SUBMIT','Submit');
define('_US_USERNAME','Username');
define('_US_FINISH','Finish');
define('_US_REGISTERNG','Could not register new user.');
define('_US_MAILOK','Receive occasional email notices <br />from administrators and moderators?');
define('_US_DISCLAIMER','Disclaimer');
define('_US_IAGREE','I agree to the above');
define('_US_UNEEDAGREE', 'Sorry, you have to agree to our disclaimer to get registered.');
define('_US_NOREGISTER','Sorry, we are currently closed for new user registrations');


// %s is username. This is a subject for email
define('_US_USERKEYFOR','User activation key for %s');

define('_US_YOURREGISTERED','You are now registered. An email containing a user activation key has been sent to the email account you provided. Please follow the instructions in the mail to activate your account. ');
define('_US_YOURREGMAILNG','You are now registered. However, we were unable to send the activation mail to your email account due to an internal error that had occurred on our server. We are sorry for the inconvenience, please send the webmaster an email notifying him/her of the situation.');
define('_US_YOURREGISTERED2','You are now registered.  Please wait for your account to be activated by the adminstrators.  You will receive an email once you are activated.  This could take a while so please be patient.');

// %s is your site name
define('_US_NEWUSERREGAT','New user registration at %s');
// %s is a username
define('_US_HASJUSTREG','%s has just registered!');

define('_US_INVALIDMAIL','ERROR: Invalid email');
define('_US_EMAILNOSPACES','ERROR: Email addresses do not contain spaces.');
define('_US_INVALIDNICKNAME','ERROR: Invalid Username');
define('_US_NICKNAMETOOLONG','Username is too long. It must be less than %s characters.');
define('_US_NICKNAMETOOSHORT','Username is too short. It must be more than %s characters.');
define('_US_NAMERESERVED','ERROR: Name is reserved.');
define('_US_NICKNAMENOSPACES','There cannot be any spaces in the Username.');
define('_US_NICKNAMETAKEN','ERROR: Username taken.');
define('_US_EMAILTAKEN','ERROR: Email address already registered.');
define('_US_ENTERPWD','ERROR: You must provide a password.');
define('_US_SORRYNOTFOUND','Sorry, no corresponding user info was found.');


define('_US_USERINVITE', 'Membership invitation');
define('_US_INVITENONE','ERROR: Registration is by invitation only.');
define('_US_INVITEINVALID','ERROR: Incorrect invitation code.');
define('_US_INVITEEXPIRED','ERROR: Invitation code is already used or expired.');

define('_US_INVITEBYMEMBER','Only an existing member can invite new members; please request an invitation email from some registered member.');
define('_US_INVITEMAILERR','We were unable to send the mail with registration link to your email account due to an internal error that had occurred on our server. We are sorry for the inconvenience, please try again and if problem persists, do send the webmaster an email notifying him/her of the situation. <br />');
define('_US_INVITEDBERR','We were unable to process your registration request due to an internal error. We are sorry for the inconvenience, please try again and if problem persists, do send the webmaster an email notifying him/her of the situation. <br />');
define('_US_INVITESENT','An email containing registration link has been sent to the email account you provided. Please follow the instructions in the mail to register your account. This could take few minutes so please be patient.');
// %s is your site name
define('_US_INVITEREGLINK','Registration invitation from %s');


// %s is your site name
define('_US_NEWPWDREQ','New Password Request at %s');
define('_US_YOURACCOUNT', 'Your account at %s');

define('_US_MAILPWDNG','mail_password: could not update user entry. Contact the Administrator');

// %s is a username
define('_US_PWDMAILED','Password for %s mailed.');
define('_US_CONFMAIL','Confirmation Mail for %s mailed.');
define('_US_ACTVMAILNG', 'Failed sending notification mail to %s');
define('_US_ACTVMAILOK', 'Notification mail to %s sent.');

//%%%%%%		File Name userinfo.php 		%%%%%
define('_US_SELECTNG','No User Selected! Please go back and try again.');
define('_US_PM','PM');
define('_US_ICQ','ICQ');
define('_US_AIM','AIM');
define('_US_YIM','YIM');
define('_US_MSNM','MSNM');
define('_US_LOCATION','Location');
define('_US_OCCUPATION','Occupation');
define('_US_INTEREST','Interest');
define('_US_SIGNATURE','Signature');
define('_US_EXTRAINFO','Extra Info');
define('_US_EDITPROFILE','Edit Profile');
define('_US_LOGOUT','Logout');
define('_US_INBOX','Inbox');
define('_US_MEMBERSINCE','Member Since');
define('_US_RANK','Rank');
define('_US_POSTS','Comments/Posts');
define('_US_LASTLOGIN','Last Login');
define('_US_ALLABOUT','All about %s');
define('_US_STATISTICS','Statistics');
define('_US_MYINFO','My Info');
define('_US_BASICINFO','Basic information');
define('_US_MOREABOUT','More About Me');
define('_US_SHOWALL','Show All');

//%%%%%%		File Name edituser.php 		%%%%%
define('_US_PROFILE','Profile');
define('_US_REALNAME','Real Name');
define('_US_SHOWSIG','Always attach my signature');
define('_US_CDISPLAYMODE','Comments Display Mode');
define('_US_CSORTORDER','Comments Sort Order');
define('_US_PASSWORD','Password');
define('_US_TYPEPASSTWICE','(type a new password twice to change it)');
define('_US_SAVECHANGES','Save Changes');
define('_US_NOEDITRIGHT',"Sorry, you don't have the right to edit this user's info.");
define('_US_PASSNOTSAME','Both passwords are different. They must be identical.');
define('_US_PWDTOOSHORT','Sorry, your password must be at least <b>%s</b> characters long.');
define('_US_PROFUPDATED','Your Profile Updated!');
define('_US_USECOOKIE','Store my user name in a cookie for 1 year');
define('_US_NO','No');
define('_US_DELACCOUNT','Delete Account');
define('_US_MYAVATAR', 'My Avatar');
define('_US_UPLOADMYAVATAR', 'Upload Avatar');
define('_US_MAXPIXEL','Max Pixels');
define('_US_MAXIMGSZ','Max Image Size (Bytes)');
define('_US_SELFILE','Select file');
define('_US_OLDDELETED','Your old avatar will be deleted!');
define('_US_CHOOSEAVT', 'Choose avatar from the available list');
define('_US_SELECT_THEME', 'Default Theme');
define('_US_SELECT_LANG', 'Default Language');

define('_US_PRESSLOGIN', 'Press the button below to login');

define('_US_ADMINNO', 'User in the webmasters group cannot be removed');
define('_US_GROUPS', 'User\'s Groups');
?>