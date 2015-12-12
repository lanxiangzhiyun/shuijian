
BQ.add('Pet', function(W,CLASS_NAME){

	var defaultConfig = {
		com: 'bqpet', //容器
		but: '#bqpetbut', //触发按钮
		fn:function(o){}
	};

    function ClassObj(eventType, config) {
        var self = this;
        if (!(self instanceof ClassObj)) {
            return new ClassObj(eventType, W.merge(defaultConfig, config));
        }
        var config = self.config = config;
		eventType = eventType ||'pet';
		var but = $(config.but);
	
		var dog={"100_small_B_c_h":"巴哥","101_small_B_c_h":"博美","102_small_H_c_h":"蝴蝶","103_small_X_c_h":"西施","104_small_K_c_h":"柯基","105_small_B_c_h":"比熊","106_small_J_c_h":"京巴","107_small_Y_c_h":"约克夏","108_small_M_c_h":"迷你品","109_small_J_c_h":"吉娃娃","110_small_S_c_h":"丝毛梗","111_small_R_b_j":"日本尖嘴","112_small_M_c_h":"玛尔济斯","113_small_W_b_j":"玩具贵宾","114_small_F_c_h":"法国斗牛","115_small_G_b_j":"迷你贵宾","116_small_G_c_h":"刚毛猎狐梗","117_small_X_b_j":"西高地白梗","118_small_P_b_j":"平毛猎狐梗","119_small_M_c_h":"迷你雪纳瑞","120_small_L_b_j":"标准短毛腊肠","121_small_M_b_j":"迷你长毛腊肠","122_small_M_b_j":"迷你短毛腊肠","123_small_B_b_j":"标准长毛腊肠","124_small_B_b_j":"标准刚毛腊肠","125_small_T_b_j":"兔型短毛腊肠","126_small_T_b_j":"兔型刚毛腊肠","127_small_T_b_j":"兔型长毛腊肠","128_small_C_b_j":"串串（小型）","129_small_M_b_j":"迷你刚毛腊肠","130_small_A_b_j":"阿尔卑斯达切斯勃拉克犬","131_small_G_b_j":"冠毛","132_small_R_b_j":"日本狆","133_small_C_b_j":"查理王","134_small_Y_b_j":"英国玩具梗","135_small_K_b_j":"卡迪根柯基","136_small_A_b_j":"爱尔兰梗","137_small_X_b_j":"小狮子狗","138_small_X_b_j":"西藏猎犬","139_small_B_b_j":"贝灵顿梗","140_small_M_b_j":"棉花面纱犬","141_small_B_b_j":"比利时猎犬","142_small_Y_b_j":"意大利狐狸犬","143_small_C_b_j":"查尔斯王小猎犬","144_small_R_b_j":"日本梗","145_small_J_b_j":"捷克梗","146_small_H_b_j":"猴面梗","147_small_B_b_j":"巴西梗","148_small_X_b_j":"西藏梗","149_small_S_b_j":"斯凯梗","150_small_D_b_j":"德国猎梗","151_small_S_b_j":"苏格兰梗","152_small_R_b_j":"瑞典腊肠犬","153_small_X_b_j":"小瑞士猎犬","154_small_B_b_j":"秘鲁无毛犬","155_small_B_b_j":"冰岛牧羊犬","156_small_A_b_j":"阿提桑诺曼底短腿犬","157_small_B_b_j":"伯德梗","158_small_H_b_j":"湖畔梗","159_small_L_b_j":"罗福梗","160_small_K_b_j":"凯恩梗","161_small_B_b_j":"波士顿梗","162_small_N_b_j":"诺维茨梗","163_small_R_b_j":"瑞典柯基犬","164_small_M_b_j":"曼彻斯特梗","165_small_D_b_j":"短脚长身梗","166_small_X_b_j":"西里汉姆梗","167_small_N_b_j":"挪威卢德杭犬","168_small_H_b_j":"哈威那伴随犬","169_small_A_b_j":"爱尔兰峡谷梗","170_small_B_b_j":"波伦亚伴随犬","171_small_B_b_j":"布拉塞尔猎犬","172_small_J_b_j":"杰克拉赛尔梗","173_small_X_b_j":"小布拉班特猎犬","174_small_P_b_j":"帕森拉·罗赛尔","175_small_H_b_j":"荷兰斯姆茨杭德犬","176_small_D_b_j":"大格里芬旺德短腿犬","177_small_L_b_j":"蓝色加斯科涅短腿犬","178_small_P_b_j":"佩蒂格里芬旺德短腿犬","179_small_W_b_j":"威斯特伐利亚·达切斯勃拉克犬","180_medium_B_c_h":"边牧","181_medium_S_c_h":"松狮","182_medium_X_c_h":"喜乐蒂","183_medium_B_c_h":"巴吉度","184_medium_H_c_h":"哈士奇","185_medium_X_c_h":"雪纳瑞","186_medium_Z_b_j":"中型贵宾","187_medium_B_b_j":"标准贵宾","188_medium_Y_c_h":"英国可卡","189_medium_C_b_j":"串串（中型）","190_medium_S_c_h":"沙皮","191_medium_C_c_h":"柴犬","192_medium_M_c_h":"美国可卡","193_medium_Y_c_h":"英国斗牛","194_medium_Y_c_h":"英国史宾格","195_medium_W_b_j":"威尔士史宾格","196_medium_B_c_h":"比格","197_medium_H_b_j":"惠比特","198_medium_S_b_j":"四国犬","199_medium_N_b_j":"牛头梗 ","200_medium_B_b_j":"北海道犬","201_medium_B_b_j":"巴辛吉犬","202_medium_R_b_j":"瑞士猎犬","203_medium_W_b_j":"威尔士梗","204_medium_X_b_j":"西班牙水犬","205_medium_P_b_j":"葡萄牙水犬","206_medium_B_b_j":"布列塔尼犬","207_medium_D_b_j":"德国宾莎犬","208_medium_D_b_j":"大胡子柯利犬","209_medium_Y_b_j":"意大利指示犬","210_medium_A_b_j":"澳牧","211_medium_B_b_j":"波利","212_medium_J_b_j":"纪州犬","213_medium_C_b_j":"瓷器犬","214_medium_L_b_j":"猎兔犬","215_medium_J_b_j":"迦南犬","216_medium_F_b_j":"芬兰猎犬","217_medium_D_b_j":"德国猎犬","218_medium_X_b_j":"希腊猎犬","219_medium_T_b_j":"田野猎犬","220_medium_F_b_j":"法国水犬","221_medium_N_b_j":"挪威猎犬","222_medium_D_b_j":"斗牛獒犬","223_medium_M_b_j":"美国水猎犬","224_medium_A_b_j":"爱尔兰水犬","225_medium_N_b_j":"挪威牧羊犬","226_medium_Y_b_j":"英法小猎犬","227_medium_X_b_j":"西班牙猎犬","228_medium_H_b_j":"韩国金刀犬","229_medium_F_b_j":"芬兰驯鹿犬","230_medium_X_b_j":"西西里猎犬","231_medium_P_b_j":"葡萄牙牧羊犬","232_medium_B_b_j":"比利时牧羊犬","233_medium_S_b_j":"斯洛伐克猎犬","234_medium_P_b_j":"葡萄牙指示犬","235_medium_M_b_j":"墨西哥无毛犬","236_medium_R_b_j":"瑞典拉普猎犬","237_medium_K_b_j":"克罗地亚牧羊犬","238_medium_A_b_j":"奥地利黑褐猎犬","239_medium_D_b_j":"短毛意大利猎犬","240_medium_X_b_j":"小型荷兰水猎犬","241_medium_A_b_j":"澳大利亚短毛宾莎犬","242_medium_C_b_j":"长毛比利牛斯牧羊犬","243_medium_X_b_j":"新斯科舍猎鸭寻猎犬","244_medium_B_b_j":"比特","245_medium_M_b_j":"马地犬","246_medium_J_b_j":"甲斐犬","247_medium_B_b_j":"波密犬","248_medium_X_b_j":"席勒猎犬","249_medium_H_b_j":"海根猎犬","250_medium_F_b_j":"芬兰狐犬","251_medium_G_b_j":"格陵兰犬","252_medium_M_b_j":"马略卡獒","253_medium_A_b_j":"澳洲牧牛犬","254_medium_A_b_j":"艾瑞格斯犬","255_medium_H_b_j":"哈尔登猎犬","256_medium_F_b_j":"法老王猎犬","257_medium_N_b_j":"挪威猎鹿犬","258_medium_T_b_j":"泰国脊背犬","259_medium_K_b_j":"克伦勃猎犬","260_medium_O_b_j":"欧亚大陆犬","261_medium_A_b_j":"阿图瓦猎犬","262_medium_L_b_j":"罗曼娜水犬","263_medium_T_b_j":"提洛尔猎犬","264_medium_A_b_j":"阿登牧牛犬","265_medium_T_b_j":"泰托拉牧羊犬","266_medium_A_b_j":"阿彭则牧牛犬","267_medium_S_b_j":"斯莫兰德猎犬","268_medium_S_b_j":"塞尔维亚猎犬","269_medium_K_b_j":"克龙弗兰德犬","270_medium_K_b_j":"卡累利亚熊犬","271_medium_F_b_j":"芬兰拉普猎犬","272_medium_S_b_j":"斯塔比荷猎犬","273_medium_K_b_j":"卡塔兰牧羊犬","274_medium_N_b_j":"诺波丹狐狸犬","275_medium_H_b_j":"汉密尔顿猎犬","276_medium_B_b_j":"波萨维茨猎犬","277_medium_H_b_j":"汗挪威嗅猎犬","278_medium_A_b_j":"埃特那岛猎犬","279_medium_K_b_j":"卡斯特牧羊犬","280_medium_E_b_j":"恩特布山地犬","281_medium_X_b_j":"西帕凯牧羊犬","282_medium_A_b_j":"澳大利亚卡尔比","283_medium_D_b_j":"东西伯利亚莱犬","284_medium_Y_b_j":"英国雪达蹲猎犬","285_medium_J_b_j":"加斯科涅小蓝犬","286_medium_H_b_j":"灰色挪威猎鹿犬","287_medium_P_b_j":"蓬托德梅尔猎犬","288_medium_C_b_j":"粗毛意大利猎犬","289_medium_O_b_j":"欧式俄国莱卡犬","290_medium_S_b_j":"斯塔福郡斗牛梗","291_medium_P_b_j":"葡萄牙波登哥犬","292_medium_X_b_j":"小明斯特兰德犬","293_medium_A_b_j":"阿特拉斯牧羊犬","294_medium_B_b_j":"波兰低地牧羊犬","295_medium_B_b_j":"贝加马斯卡牧羊犬","296_medium_A_b_j":"爱尔兰软毛麦色梗","297_medium_Y_b_j":"依斯特拉粗毛猎犬","298_medium_S_b_j":"苏赛克斯长耳猎犬","299_medium_S_b_j":"斯提瑞恩粗毛猎犬","300_medium_Z_b_j":"中型格里芬狩猎犬","301_medium_X_b_j":"西西伯利亚莱卡犬","302_medium_K_b_j":"卡斯托莱博瑞罗犬","303_medium_M_b_j":"蒙特内哥罗山猎犬","304_medium_S_b_j":"塞尔维亚三色猎犬","305_medium_B_b_j":"巴伐利亚山嗅猎犬","306_medium_B_b_j":"波斯尼亚粗毛猎犬","307_medium_Y_b_j":"依斯特拉短毛猎犬","308_medium_Y_b_j":"意大利凯因克尔索犬","309_medium_L_b_j":"蓝色加斯科涅格里芬犬","310_large_D_c_h":"德牧","311_large_Z_c_h":"藏獒","312_large_G_c_h":"古牧","313_large_S_c_h":"苏牧","314_large_J_c_h":"金毛","315_large_B_c_h":"斑点狗","316_large_D_c_h":"大白熊","317_large_S_c_h":"萨摩耶","318_large_A_c_h":"阿拉斯加","319_large_L_c_h":"拉布拉多","320_large_C_b_j":"串串（大型）","321_large_Q_c_h":"秋田","322_large_D_c_h":"杜宾","323_large_Q_c_h":"拳师","324_large_A_c_h":"阿富汗","325_large_J_c_h":"巨型雪纳瑞","326_large_M_b_j":"美国秋田犬","327_large_L_b_j":"灵缇","328_large_D_b_j":"杜高","329_large_D_b_j":"大丹","330_large_S_b_j":"圣伯纳","331_large_L_b_j":"罗威纳","332_large_W_b_j":"万能梗","333_large_K_b_j":"可蒙犬","334_large_B_b_j":"伯恩山","335_large_F_b_j":"法国狼犬","336_large_E_b_j":"俄国灵缇","337_large_F_b_j":"法国猎犬","338_large_X_b_j":"寻血猎犬","339_large_N_b_j":"纽芬兰犬","340_large_P_b_j":"平毛寻猎犬","341_large_Y_b_j":"英国猎狐犬","342_large_D_b_j":"大瑞士山地犬","343_large_F_b_j":"法国三色猎犬","344_large_M_b_j":"马瑞马牧羊犬 ","345_large_A_b_j":"爱尔兰红白蹲猎犬","346_large_A_b_j":"獒犬","347_large_T_b_j":"土佐犬","348_large_B_b_j":"比利犬","349_large_J_b_j":"捷克狼犬","350_large_B_b_j":"波兰猎犬","351_large_B_b_j":"比利时牧牛","352_large_H_b_j":"荷兰牧羊犬","353_large_X_b_j":"西班牙灵缇","354_large_S_b_j":"苏俄猎狼犬","355_large_A_b_j":"阿拉伯灵缇","356_large_Y_b_j":"意大利灵缇","357_large_H_b_j":"荷兰猎鸟犬","358_large_Y_b_j":"英国指示犬","359_large_G_b_j":"高加索牧羊犬","360_large_F_b_j":"法国波尔多獒","361_large_H_b_j":"黑褐猎浣熊犬","362_large_J_b_j":"卷毛寻回猎犬","363_large_D_b_j":"德国短毛指示犬","364_large_D_b_j":"德国硬毛指示犬","365_large_D_b_j":"丹麦老式指示犬","366_large_X_b_j":"匈牙利硬毛指示犬","367_large_Y_b_j":"意大利硬毛指示犬","368_large_H_b_j":"黑犬","369_large_L_b_j":"猎鹿犬","370_large_B_b_j":"巴西獒","371_large_K_b_j":"库瓦兹犬","372_large_A_b_j":"奥达猎犬","373_large_B_b_j":"波兰灵缇","374_large_W_b_j":"魏玛猎犬","375_large_P_b_j":"佩狄芬犬","376_large_L_b_j":"兰伯格犬","377_large_L_b_j":"兰西尔犬","378_large_X_b_j":"西班牙獒犬","379_large_S_b_j":"萨卢基猎犬","380_large_A_b_j":"阿札瓦克犬","381_large_F_b_j":"佛兰得猎犬","382_large_D_b_j":"大格林芬犬","383_large_X_b_j":"匈牙利灵缇","384_large_J_b_j":"卷毛指示犬","385_large_R_b_j":"瑞典猎鹿犬","386_large_G_b_j":"戈登蹲猎犬","387_large_M_b_j":"美国猎狐犬","388_large_P_b_j":"皮卡第猎犬","389_large_B_b_j":"波旁指示犬","390_large_Z_b_j":"中亚牧羊犬","391_large_A_b_j":"阿兰多獒犬","392_large_N_b_j":"那不勒斯獒","393_large_B_b_j":"比利牛斯獒犬","394_large_A_b_j":"爱尔兰猎狼犬","395_large_J_b_j":"加斯科大猎犬","396_large_A_b_j":"奥弗涅指示犬","397_large_F_b_j":"佛瑞斯安水犬","398_large_F_b_j":"法国黄白猎犬","399_large_B_b_j":"博格斯指示犬","400_large_H_b_j":"霍夫瓦尔特犬","401_large_S_b_j":"斯恰潘道斯犬","402_large_L_b_j":"罗德西亚脊背犬","403_large_A_b_j":"艾瑞格指示犬 ","404_large_D_b_j":"大英法三色猎犬","405_large_J_b_j":"加那利沃伦猎犬","406_large_D_b_j":"德国长毛指示犬","407_large_D_b_j":"德国粗毛指示犬","408_large_D_b_j":"大明斯特兰德犬","409_large_D_b_j":"大加斯科涅猎犬","410_large_D_b_j":"大英法黄白猎犬","411_large_G_b_j":"格里芬尼韦奈犬","412_large_A_b_j":"埃斯特卑拉山犬","413_large_D_b_j":"丹麦布罗荷马獒","414_large_A_b_j":"安纳托利亚牧羊犬","415_large_T_b_j":"特兰西瓦尼亚猎犬","416_large_L_b_j":"蓝色匹卡迪档猎犬","417_large_S_b_j":"圣·日尔曼指示犬","418_large_Q_b_j":"切萨皮克湾寻猎犬","419_large_E_b_j":"俄罗斯南部牧羊犬","420_large_X_b_j":"匈牙利短毛指示犬","421_large_S_b_j":"斯洛伐克楚维卡犬","422_large_F_b_j":"法国比利牛斯指示犬","423_large_Q_b_j":"浅黄不列塔尼短腿犬","424_large_Q_b_j":"浅黄布列塔尼格里芬犬","425_large_F_b_j":"法国盖斯克格尼指示犬","426_large_K_b_j":"考特哈尔斯波音达大猎犬","427_large_B_b_j":"波西米亚硬毛格里芬指示犬"};
		var cat={"A_chang_800_c_h":"奥西猫","J_chang_801_c_h":"加州闪亮猫","H_chang_802_c_h":"荒漠猫","O_chang_803_c_h":"欧洲缅甸猫","Y_chang_804_c_h":"英国短毛猫","Y_chang_805_c_h":"印度猫","Y_chang_806_c_h":"异国短毛猫","X_chang_807_c_h":"雪鞋猫","X_chang_808_c_h":"新加坡猫","X_chang_809_c_h":"暹罗猫","S_chang_810_c_h":"苏格兰折耳猫","R_chang_811":"日本短尾猫","O_chang_812":"欧西猫","M_chang_813":"缅甸猫","M_chang_814":"孟买猫","M_chang_815":"美国刚毛猫","M_chang_816":"美国短毛猫","M_chang_817":"孟加拉猫","K_chang_818":"科拉特猫","M_chang_819":"曼切堪猫","K_chang_820":"柯尼斯卷毛猫","C_chang_821":"传教士蓝猫","Z_chang_822":"重点色短毛猫","Y_chang_823":"云猫","S_chang_824":"四川简州猫","K_chang_825":"肯尼亚猫","C_chang_826":"德国卷毛猫","B_chang_827":"波米拉猫","W_chang_828":"玩具虎猫","S_chang_829":"塞伦盖蒂猫","R_chang_830":"热带草原猫","H_chang_831":"哈瓦那棕猫","E_chang_832":"俄罗斯蓝猫","C_chang_833":"东奇尼猫","C_chang_834":"东方短毛猫","C_chang_835":"德文卷毛猫","X_chang_836":"小精灵短尾猫","A_chang_837":"埃及猫","A_chang_838":"阿比西尼亚猫","C_chang_839":"电烫卷猫（拉波猫）","J_chang_840":"加拿大无毛猫","M_duan_841":"曼岛无尾猫","X_chang_842":"夏特尔猫","B_chang_843":"豹猫","M_duan_844_c_h":"美国卷耳猫","F_duan_845_c_h":"非纯种长毛猫","N_duan_846_c_h":"挪威森林猫","J_duan_847_c_h":"家庭短毛子猫","L_duan_848_c_h":"拉邦猫","M_duan_849_c_h":"美国短尾猫","B_duan_850_c_h":"布偶猫","B_duan_851_c_h":"巴厘猫","Z_duan_852_c_h":"爪洼猫","K_duan_853_c_h":"库里瑞短尾猫","N_duan_854":"内华达猫","S_duan_855":"塞尔凯克卷毛猫","M_duan_856":"缅因库恩猫","Y_duan_857":"英国长毛猫","S_duan_858":"山东狮子猫","L_duan_859":"狸花猫（玳瑁色虎斑猫）","B_duan_860":"波斯猫","X_duan_861":"喜马拉雅猫","T_duan_862":"土耳其梵猫","X_duan_863":"西伯利亚森林猫","T_duan_864":"土耳其梵科迪斯猫","S_duan_865":"索马里猫","C_duan_866":"蒂法尼猫","W_duan_867":"威尔斯猫","T_duan_868":"土耳其安哥拉猫","B_duan_869":"伯曼猫","Z_duan_870":"重点色长毛猫","B_duan_871":"波斯长毛猫","J_duan_872":"金吉拉猫","L_duan_873":"褴褛猫","M_duan_874":"曼岛无尾猫","S_duan_875":"斯可可猫"};
		var other={"Y_1000_c_h":"鹦鹉","G_1001_c_h":"鸽子","H_1002_c_h":"画眉","B_1003_c_h":"八哥 ","G_1005_c_h":"龟鳖","D_1006_c_h":"淡水观赏鱼","H_1007_c_h":"海水观赏鱼","T_1009_c_h":"兔子","C_1010_c_h":"仓鼠","S_1011_c_h":"松鼠","L_1012_c_h":"龙猫","D_1014_c_h":"貂","Z_1015_c_h":"猪","S_1016_c_h":"蛇","Q_1017_c_h":"蝈蝈","X_1018_c_h":"蟋蟀","Z_1020_c_h":"蜘蛛","X_1021_c_h":"蜥蜴","Q_1019":"其它鸣虫","Q_1013":"其它鼠类","Q_1004":"其它鸟类","Q_1008":"其它鱼类","Q_1022":"其它昆虫"};
		var zm = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

		function getDogplay(key){
			var parVal = {};
			$.each(dog,function(k,v){
				if(k.indexOf('_'+key+'_') != -1){
					var ity = getDogVariety(k),p = k.split('_');
					if(parVal[ity] == undefined) {parVal[ity] = [];};
					if(k.indexOf('c_h') != -1){
						parVal[ity].push('<li><a href="#" uid="'+p[0]+'" class="hot">'+v+'</a></li>');
					}else{
						parVal[ity].push('<li><a href="#" uid="'+p[0]+'">'+v+'</a></li>');
					}
				}
			})
			return parVal;
		}

		function getCatplay(key,max){
			var parVal = {};
			var i = 0
			$.each(cat,function(k,v){
				if(k.indexOf(key+'_') != -1){
					var ity = getCatVariety(k),p = k.split('_');
					if(parVal[ity] == undefined) {parVal[ity] = [];};
					if(k.indexOf('c_h') != -1){
						parVal[ity].push('<li><a href="#" uid="'+p[2]+'" class="hot">'+v+'</a></li>');
					}else{
						parVal[ity].push('<li><a href="#" uid="'+p[2]+'">'+v+'</a></li>');
					}
				}
			})
			return parVal;
		}

		function getOtherplay(key,max){
			var parVal = [];
			var i = 0
			$.each(other,function(k,v){
				if(k.indexOf(key+'_') != -1){
					var p = k.split('_');
					if(k.indexOf('c_h') != -1){
						parVal.push('<li><a href="#" uid="'+p[1]+'" class="hot">'+v+'</a></li>');
					}else{
						parVal.push('<li><a href="#" uid="'+p[1]+'">'+v+'</a></li>');
					}
				}
			})
			return parVal;
		}

		function getDogVariety(o){
			var Variety = 'small';
			if(o.indexOf('_medium_') != -1){
				Variety = 'medium';
			}
			if(o.indexOf('_large_') != -1){
				Variety = 'large';
			}
			return Variety;
		}

		function getCatVariety(o){
			var Variety = 'chang';
			if(o.indexOf('_duan_') != -1){
				Variety = 'duan';
			}
			return Variety;
		}
		this._getModefoID = function(i) {
			var rval = '';
			$.each(dog,function(k,v){
				if(k.indexOf(i+'_') != -1){
					rval = v;
					return false;
				};
			});
			if(rval == ''){
				$.each(cat,function(k,v){
					if(k.indexOf('_'+i+'_') != -1){
						rval = v;
						return false;
					}
				});
			}
			if(rval == ''){
				$.each(other,function(k,v){
					if(k.indexOf('_'+i+'_') != -1){
						rval = v;
						return false;
					}
				});
			}
			return rval;
		}
	//console.log( getOtherplay('G'));

		function rDom (qqb,bqb) {
			var emoTion = [];

			emoTion.push('<div class="popup_layer none" style="width:760px;" id="'+config.com+'"><div class="bg"><div class="content"> <a class="close" href="#" title="关闭">关闭</a><div class="hd">请选择宠物分类</div>');
			emoTion.push('<div class="bd"><div class="pets_cats"><!--拼音检索开始--><div class="py_tab"><ul class="bqabc"><li class="title">按拼音检索：</li>');
			for(var i = 0; i < zm.length ; i++){
				emoTion.push('<li class="c"><a href="#">'+zm[i]+'</a></li>');
			}
			emoTion.push('</ul></div>');
			emoTion.push('<div class="py_tab_content none"><ul>');
			emoTion.push('<li><a class="hot" href="#">拉布拉多</a></li>');
			emoTion.push('</ul></div><!--拼音检索结束-->');
			emoTion.push('<!--种类检索开始--><div class="pets_tab"><ul><li class="current"><a href="#">狗</a></li><li><a href="#">猫</a></li><li><a href="#">其他</a></li></ul></div>');
			emoTion.push('<div class="pets_tab_content">');
			emoTion.push('</div><!--种类检索结束-->');
			emoTion.push('</div><!--替换内容结束--> </div></div></div></div>');

			return emoTion.join('');
		}
		
		if($('#bqpetbut').length != 0)
		{
			if(!$('#'+config.com+'').is('div')){
				$('body').append(rDom());
			}
			var com = $('#'+config.com),index = 0;
			com.find('.pets_tab li').click(function(e){
				e.preventDefault();
				var _t = $(this);
				index = _t.index();
				com.find('.pets_tab li').removeClass('current').eq(index).addClass('current');
				getDoc(index);
			});
			var pyabc = com.find('.py_tab_content').hover(function(){},function(){$(this).hide();});
			com.find('ul.bqabc li.c').hover(function(e){
				var _t = $(this);
				com.find('ul.bqabc li.c a').removeClass('current');
				com.find('.py_tab').addClass('b_line');
				$(this).find('a').addClass('current');
				pyabc.show();
				domABC(pyabc,_t);
			},function(e){
				com.find('.py_tab').removeClass('b_line');
				var _rt = $(e.relatedTarget);
				//console.log(_rt.parents('.bqabc').length == 0 , _rt.parents('.py_tab_content').length == 0 , _rt[0] != com.find('.py_tab')[0] , _rt[0] != pyabc[0]);
				if (_rt.parents('.bqabc').length == 0 && _rt.parents('.py_tab_content').length == 0 && _rt[0] != com.find('.py_tab')[0] && _rt[0] != pyabc[0]) {
					pyabc.hide();
				}
				//com.find('.py_tab_content').show();
			}).click(function(e){e.preventDefault();});
			getDoc();
		}
		function getDoc (n) {
				var emoTion = [];
				n = n || 0;
				if(n == 0){
					var dog = getDogplay('c');
					emoTion.push('<h3>小型犬</h3><ul class="">');
					emoTion.push(dog.small.join(''));
					emoTion.push('</ul>');
					emoTion.push('<div class="more_btn" cod="small"><a href="#"></a></div>');
					emoTion.push('<h3>中型犬</h3><ul>');
					emoTion.push(dog.medium.join(''));
					emoTion.push('</ul>');
					emoTion.push('<div class="more_btn" cod="medium"><a href="#"></a></div>');
					emoTion.push('<h3>大型犬</h3>');
					emoTion.push('<ul class="expand ">');
					emoTion.push(dog.large.join(''));
					emoTion.push('</ul>');
					emoTion.push('<div class="more_btn" cod="large"><a href="#"></a></div>');
				}else if(n == 1){
					var cat = getCatplay('c');
					emoTion.push('<h3>长毛猫</h3><ul class="">');
					emoTion.push(cat.chang.join(''));
					emoTion.push('</ul>');
					emoTion.push('<div class="more_btn" cod="chang"><a href="#"></a></div>');
					emoTion.push('<h3>大型犬</h3>');
					emoTion.push('<ul class="expand">');
					emoTion.push(cat.duan.join(''));
					emoTion.push('</ul>');
					emoTion.push('<div class="more_btn" cod="duan"><a href="#"></a></div>');
				}else{
					var other = getOtherplay('c');
					emoTion.push('<h3>其他宠物</h3>');
					emoTion.push('<ul class="expand">');
					emoTion.push(other.join(''));
					emoTion.push('</ul>');
					emoTion.push('<div class="more_btn" cod="other"><a href="#"></a></div>');
				}
				var pets = com.find('.pets_tab_content');
				pets.html(emoTion.join(''));
				pets.find('.more_btn').click(function(e){
					e.preventDefault();
					var _t = $(this),_mba = _t.find('a'),_cod=_t.attr('cod');
					if(_mba.hasClass('e')){
						_mba.removeClass('e');
						_t.prev().prev().show();
						_t.prev().hide();
					}else{
						_mba.addClass('e');
						if(!_t.prev().prev().is('ul')){
							_t.prev().after('<ul>'+getMode(_cod).join('')+'</ul>');
							_t.prev().prev().hide();
							_t.prev().show();
						}else{
							_t.prev().prev().hide();
							_t.prev().show();
						}
					}
					rdEven();
				})
				rdEven();
		}
		
		function domABC (d,t) {
			var vdata;
			if(index == 0){
				var dog = getDogplay(t.text());
				vdata = dog.small != undefined ? dog.small.join('') : '';
				vdata += dog.medium != undefined ? dog.medium.join('') : '';
				vdata += dog.large != undefined ? dog.large.join('') : '';
				vdata = vdata || '此类别下无信息';
				d.find('ul').html(vdata);
			}else if(index == 1){
				var cat = getCatplay(t.text());
				vdata = cat.chang != undefined ? cat.chang.join('') : '';
				vdata += cat.duan != undefined ? cat.duan.join('') : '';
				vdata = vdata || '此类别下无信息';
				d.find('ul').html(vdata);
			}else{
				vdata = getOtherplay(t.text()).join('');
				vdata = vdata || '此类别下无信息';
				d.find('ul').html(vdata);
			}
			rdHoverEven();
		}

		function getMode (_cod) {
			var vdata;
			if(_cod == 'small'){
				vdata = getDogplay('small').small;
			}else if (_cod == 'medium') {
				vdata = getDogplay('medium').medium;
			}else if (_cod == 'large') {
				vdata = getDogplay('large').large;
			}else if (_cod == 'chang') {
				vdata = getCatplay('chang').chang;
			}else if (_cod == 'duan') {
				vdata = getCatplay('duan').duan;
			}else if (_cod == 'other') {
				vdata = getOtherplay('');
			}
			return vdata;
		}
		if($('#bqpetbut').length != 0){
			var _lbox = BQ.widget.LayerBox('struc',{struc:'#'+config.com,scroll:false,'zIndex':999999});
			
			but.click(function(e){
				e.preventDefault();
				_lbox.alert();
				$('#'+config.com).css('top',$(document).scrollTop());
			});
		}

		function rdEven () {
			$('#bqpet .pets_tab_content ul a,#bqpet .py_tab_content ul a').unbind().click(function(e){
				e.preventDefault();
				config.fn($(this));
				_lbox.close();
				//console.log($(this).attr('uid'))
			})
		}
		
		function rdHoverEven () {
			$('#bqpet .py_tab_content ul a').unbind().click(function(e){
				e.preventDefault();
				config.fn($(this));
				_lbox.close();
				//console.log($(this).attr('uid'))
			})
		}

		//_lbox3.close();
	}
	W.augment(ClassObj, {
			getModefoID:function(i){return this._getModefoID(i);}
	 })
	return ClassObj;
})