{"filter":false,"title":"PasswordReset.php","tooltip":"/app/Models/PasswordReset.php","ace":{"folds":[],"scrolltop":0,"scrollleft":0,"selection":{"start":{"row":10,"column":1},"end":{"row":10,"column":1},"isBackwards":false},"options":{"guessTabSize":true,"useWrapMode":false,"wrapToView":true},"firstLineState":{"row":104,"mode":"ace/mode/php"}},"hash":"13ca5fa4e28fe8e5e7c0f7695529dc5289a34a05","undoManager":{"mark":0,"position":0,"stack":[[{"start":{"row":0,"column":0},"end":{"row":23,"column":1},"action":"remove","lines":["<?php","","namespace App\\Models;","","use Illuminate\\Database\\Eloquent\\Model;","","/**"," * Class PasswordReset"," */","class PasswordReset extends Model","{","    protected $table = 'password_resets';","","    public $timestamps = true;","","    protected $fillable = [","        'email',","        'token'","    ];","","    protected $guarded = [];","","        ","}"],"id":2},{"start":{"row":0,"column":0},"end":{"row":23,"column":1},"action":"insert","lines":["<?php","","namespace App\\Models;","","use Illuminate\\Database\\Eloquent\\Model;","","/**"," * Class PasswordReset"," */","class PasswordReset extends Model","{","    protected $table = 'password_resets';","","    public $timestamps = false;","","    protected $fillable = [","        'email',","        'token'","    ];","","    protected $guarded = [];","","        ","}"]}]]},"timestamp":1486892511908}