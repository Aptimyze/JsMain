<div class="reg_pad1 fontreg">
  <div class="brdrb_1">
    <div class="fullwid txtc f15 tabone clearfix">
      ~if $PAGE eq "JSPCR1"`
      <a class="sin_opt reg_active">Account details</a> 
      ~/if`
      ~if $PAGE eq "JSPCR2"`
      <a style="cursor:default" class="fl reg_active">Profile details</a>
      <a style="cursor:default" class="fl">Education & Profession</a>
      <a style="cursor:default" class="fl">Lifestyle & Family</a>
      ~/if`
      ~if $PAGE eq "JSPCR3"`
      <a style="cursor:default" class="fl">Profile details</a>
      <a style="cursor:default" class="fl reg_active">Education & Profession</a>
      <a style="cursor:default" class="fl">Lifestyle & Family</a>
      ~/if`
      ~if $PAGE eq "JSPCR4"`
      <a style="cursor:default" class="fl">Profile details</a>
      <a style="cursor:default" class="fl">Education & Profession</a>
      <a style="cursor:default" class="fl reg_active">Lifestyle & Family</a>
      ~/if`
      ~if $PAGE eq "DPP"`
      <div class="pt30 pb30 txtc fontlig f22 colrw"> Welcome ~if isset($name)` ~$name` ~/if`! We have set some partner preferences for you  </div>
      ~/if`
    </div>
  </div>
</div>
