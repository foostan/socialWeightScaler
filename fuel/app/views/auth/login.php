<?php echo $errmsg ?>
<p>
<form action="/auth/login" method="POST">
<!-- CSRF対策 -->
<input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
<div>
ユーザー名：<input type-"text" name="username" value="" />
</div>
<div>
パスワード：<input type-"password" name="password" value="" />
</div>
<input type="submit" value="ログイン" />
</form>
</p>
<p><a href="/auth/create">新規ユーザー作成</a></p>

