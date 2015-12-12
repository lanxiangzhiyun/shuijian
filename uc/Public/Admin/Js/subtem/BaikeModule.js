/**
 *
 * 　　　┏┓　　　┏┓
 * 　　┏┛┻━━━┛┻┓
 * 　　┃　　　　　　　┃
 * 　　┃　　　━　　　┃
 * 　　┃　┳┛　┗┳　┃
 * 　　┃　　　　　　　┃
 * 　　┃　　　┻　　　┃
 * 　　┃　　　　　　　┃
 * 　　┗━┓　　　┏━┛Code is far away from bug with the animal protecting
 * 　　　　┃　　　┃    神兽保佑,代码无bug
 * 　　　　┃　　　┃
 * 　　　　┃　　　┗━━━┓
 * 　　　　┃　　　　　 ┣┓
 * 　　　　┃　　　　 ┏┛
 * 　　　　┗┓┓┏━┳┓┏┛
 * 　　　　　┃┫┫　┃┫┫
 * 　　　　　┗┻┛　┗┻┛
 *
 */

function addmodule(ind,i) {
	/*图文1模块html*/
	var tuwen1 = '<div class="tuwen1 contnetbox module1" id="module_1_'+i+'">\
	    <div class="con_title clearfix">\
	        <h3>图文1</h3>\
	        <p>序号：<input type="text" name="module[1]['+i+'][modid]" class="noempty isnum" data-empty="图文1模块序号"><em class="colorred">*</em></p>\
	        <input type="button" value="删除模块" class="removemodule">\
	    </div>\
	    <div class="con_slt">\
	        <img src="../../../Public/Admin/Images/images/tuwen1_s.png" height="248" width="801">\
	    </div>\
	    <table class="con_table">\
	        <tr>\
	            <th>图片1<em class="colorred">*</em></th>\
	            <td>\
	                <span class="input_area">\
                        <div class="btn coupon_btn">\
                           <span>选择图片</span>\
                           <input  type="file" name="upload" >\
                           <input type="hidden" value="" name="module[1]['+i+'][0][pic]" class="pic_path noempty" data-empty="图文1图片1">\
                        </div>\
                        <img src="" class="show_img" width="30" height="30" style="display:none" />\
                    </span>\
	                <span>(尺寸260*120)</span>\
	            </td>\
	        </tr>\
	        <tr>\
	            <th>图片1超链接</th>\
	            <td class="con_link">\
	                <input type="text" name="module[1]['+i+'][0][link]" class="bhttp">\
	                <span>(请以http://开头)</span>\
	            </td>\
	        </tr>\
	        <tr>\
	            <th>图片1标签<em class="colorred">*</em></th>\
	            <td class="con_link">\
	                <input type="text" name="module[1]['+i+'][0][tab]" class="noempty" data-empty="图文1图片1标签">\
	            </td>\
	        </tr>\
	    </table>\
	    <table class="con_table">\
	        <tr>\
	            <th>文章1标题<em class="colorred">*</em></th>\
	            <td  class="con_link">\
	                <input type="text" name="module[1]['+i+'][1][tit]" class="noempty" data-empty="图文1文章1标题">\
	            </td>\
	        </tr>\
	        <tr>\
	            <th>文章1超链接<em class="colorred">*</em></th>\
	            <td class="con_link">\
	                <input type="text" name="module[1]['+i+'][1][link]" class="noempty bhttp" data-empty="图文1文章1超链接">\
	                <span>(请以http://开头)</span>\
	            </td>\
	        </tr>\
	        <tr>\
	            <th>文章1简介<em class="colorred">*</em></th>\
	            <td class="con_link">\
	                <textarea name="module[1]['+i+'][1][art]" class="noempty" data-empty="图文1文章1简介"></textarea>\
	            </td>\
	        </tr>\
	    </table>\
	    <table class="con_table">\
	        <tr>\
	            <th>文章2标题<em class="colorred">*</em></th>\
	            <td  class="con_link">\
	                <input type="text" name="module[1]['+i+'][2][tit]" class="noempty" data-empty="图文1文章2标题">\
	                <span>(请保持14字以内)</span>\
	            </td>\
	        </tr>\
	        <tr>\
	            <th>文章2超链接<em class="colorred">*</em></th>\
	            <td class="con_link">\
	                <input type="text" name="module[1]['+i+'][2][link]" class="noempty bhttp" data-empty="图文1文章2超链接">\
	                <span>(请以http://开头)</span>\
	            </td>\
	        </tr>\
	        <tr>\
	            <th>文章2简介<em class="colorred">*</em></th>\
	            <td class="con_link">\
	                <textarea name="module[1]['+i+'][2][art]" class="noempty" data-empty="图文1文章2简介"></textarea>\
	                <span>(请保持76字以内)</span>\
	            </td>\
	        </tr>\
	    </table>\
	    <table class="con_table">\
	        <tr>\
	            <th>文章3标题<em class="colorred">*</em></th>\
	            <td  class="con_link">\
	                <input type="text" name="module[1]['+i+'][3][tit]" class="noempty" data-empty="图文1文章3标题">\
	                <span>(请保持14字以内)</span>\
	            </td>\
	        </tr>\
	        <tr>\
	            <th>文章3超链接<em class="colorred">*</em></th>\
	            <td class="con_link">\
	                <input type="text" name="module[1]['+i+'][3][link]" class="noempty bhttp" data-empty="图文1文章2超链接">\
	                <span>(请以http://开头)</span>\
	            </td>\
	        </tr>\
	        <tr>\
	            <th>文章3简介<em class="colorred">*</em></th>\
	            <td class="con_link">\
	                <textarea name="module[1]['+i+'][3][art]" class="noempty" data-empty="图文1文章3简介"></textarea>\
	                <span>(请保持76字以内)</span>\
	            </td>\
	        </tr>\
	    </table>\
	    <table class="con_table">\
	        <tr>\
	            <th>图片2<em class="colorred">*</em></th>\
	            <td>\
	                <span class="input_area">\
                        <div class="btn coupon_btn">\
                           <span>选择图片</span>\
                           <input  type="file" name="upload" >\
                           <input type="hidden" value="" name="module[1]['+i+'][4][pic]" class="pic_path noempty" data-empty="图文1图片2">\
                        </div>\
                        <img src="" class="show_img" width="30" height="30" style="display:none" />\
                    </span>\
	                <span>(尺寸260*120)</span>\
	            </td>\
	        </tr>\
	        <tr>\
	            <th>文章4超链接<em class="colorred">*</em></th>\
	            <td class="con_link">\
	                <input type="text" name="module[1]['+i+'][4][link]" class="noempty bhttp" data-empty="图文1文章4超链接">\
	                <span>(请以http://开头)</span>\
	            </td>\
	        </tr>\
	        <tr>\
	            <th>图片2标签<em class="colorred">*</em></th>\
	            <td class="con_link">\
	                <input type="text" name="module[1]['+i+'][4][tab]" class="noempty" data-empty="图文1图片2标签">\
	            </td>\
	        </tr>\
	        <tr>\
	            <th>文章4简介<em class="colorred">*</em></th>\
	            <td class="con_link">\
	                <textarea name="module[1]['+i+'][4][art]" class="noempty" data-empty="图文1文章4简介"></textarea>\
	                <span>(请保持76字以内)</span>\
	            </td>\
	        </tr>\
	    </table>\
	    <table class="con_table">\
	        <tr>\
	            <th>图片3<em class="colorred">*</em></th>\
	            <td>\
	                <span class="input_area">\
                        <div class="btn coupon_btn">\
                           <span>选择图片</span>\
                           <input  type="file" name="upload" >\
                           <input type="hidden" value="" name="module[1]['+i+'][5][pic]" class="pic_path noempty" data-empty="图文1图片3">\
                        </div>\
                        <img src="" class="show_img" width="30" height="30" style="display:none" />\
                    </span>\
	                <span>(尺寸260*120)</span>\
	            </td>\
	        </tr>\
	        <tr>\
	            <th>文章5超链接<em class="colorred">*</em></th>\
	            <td class="con_link">\
	                <input type="text" name="module[1]['+i+'][5][link]" class="noempty bhttp" data-empty="图文1文章5超链接">\
	                <span>(请以http://开头)</span>\
	            </td>\
	        </tr>\
	        <tr>\
	            <th>图片3标签<em class="colorred">*</em></th>\
	            <td class="con_link">\
	                <input type="text" name="module[1]['+i+'][5][tab]" class="noempty" data-empty="图文1图片3标签">\
	            </td>\
	        </tr>\
	        <tr>\
	            <th>文章5简介<em class="colorred">*</em></th>\
	            <td class="con_link">\
	                <textarea name="module[1]['+i+'][5][art]" class="noempty" data-empty="图文1文章5简介"></textarea>\
	                <span>(请保持76字以内)</span>\
	            </td>\
	        </tr>\
	    </table>\
	</div>';
    /*图文2模块html*/
	var tuwen2 = '<div class="tuwen2 contnetbox module2" id="module_2_'+i+'">\
        <div class="con_title clearfix">\
            <h3>图文2</h3>\
            <p>序号：<input type="text" name="module[2]['+i+'][modid]" class="noempty isnum" data-empty="图文2模块序号"><em class="colorred">*</em></p>\
            <input type="button" value="删除模块" class="removemodule">\
        </div>\
        <div class="con_slt">\
            <img src="../../../Public/Admin/Images/images/tuwen2_s.png" height="99" width="775">\
        </div>\
        <table class="con_table">\
            <tr>\
                <th>文章1图片<em class="colorred">*</em></th>\
                <td>\
                    <span class="input_area">\
                        <div class="btn coupon_btn">\
                           <span>选择图片</span>\
                           <input  type="file" name="upload" >\
                           <input type="hidden" value="" name="module[2]['+i+'][1][pic]" class="pic_path noempty" data-empty="图文2文章1图片">\
                        </div>\
                        <img src="" class="show_img" width="30" height="30" style="display:none" />\
                    </span>\
                    <span>(尺寸200*150)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章1标题<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[2]['+i+'][1][tit]" class="noempty" data-empty="图文2文章1标题">\
                    <span>(请保持14字以内)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章1链接<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[2]['+i+'][1][link]" class="noempty bhttp" data-empty="图文2文章1链接">\
                    <span>(请以http://开头)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章1简介<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <textarea name="module[2]['+i+'][1][art]" class="noempty" data-empty="图文2文章1简介"></textarea>\
                    <span>(请保持66字以内)</span>\
                </td>\
            </tr>\
        </table>\
        <table class="con_table">\
            <tr>\
                <th>文章2图片<em class="colorred">*</em></th>\
                <td>\
                    <span class="input_area">\
                        <div class="btn coupon_btn">\
                           <span>选择图片</span>\
                           <input  type="file" name="upload" >\
                           <input type="hidden" value="" name="module[2]['+i+'][2][pic]" class="pic_path noempty" data-empty="图文2文章2图片">\
                        </div>\
                        <img src="" class="show_img" width="30" height="30" style="display:none" />\
                    </span>\
                    <span>(尺寸200*150)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章2标题<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[2]['+i+'][2][tit]" class="noempty" data-empty="图文2文章2标题">\
                    <span>(请保持14字以内)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章2链接<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[2]['+i+'][2][link]" class="noempty bhttp" data-empty="图文2文章2链接">\
                    <span>(请以http://开头)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章2简介<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <textarea name="module[2]['+i+'][2][art]" class="noempty" data-empty="图文2文章2简介"></textarea>\
                    <span>(请保持66字以内)</span>\
                </td>\
            </tr>\
        </table>\
    </div>';
    /*图文3模块html*/
	var tuwen3 = '<div class="tuwen3 contnetbox module3" id="module_3_'+i+'">\
        <div class="con_title clearfix">\
            <h3>图文3</h3>\
            <p>序号：<input type="text" name="module[3]['+i+'][modid]" class="noempty isnum" data-empty="图文3模块序号"><em class="colorred">*</em></p>\
            <input type="button" value="删除模块" class="removemodule">\
        </div>\
        <div class="con_slt">\
            <img src="../../../Public/Admin/Images/images/tuwen3_s.png" height="218" width="700" >\
        </div>\
        <table class="con_table">\
            <tr>\
                <th>文章1图片<em class="colorred">*</em></th>\
                <td>\
                    <span class="input_area">\
                        <div class="btn coupon_btn">\
                           <span>选择图片</span>\
                           <input  type="file" name="upload" >\
                           <input type="hidden" value="" name="module[3]['+i+'][1][pic]" class="pic_path noempty" data-empty="图文3文章1图片">\
                        </div>\
                        <img src="" class="show_img" width="30" height="30" style="display:none" />\
                    </span>\
                    <span>(尺寸960*330)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章1标题<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[3]['+i+'][1][tit]" class="noempty" data-empty="图文3文章1标题">\
                    <span>(请保持14字以内)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章1链接<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[3]['+i+'][1][link]" class="noempty bhttp" data-empty="图文3文章1链接">\
                    <span>(请以http://开头)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章1简介<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <textarea  name="module[3]['+i+'][1][art]" class="noempty" data-empty="图文3文章1简介"></textarea>\
                    <span>(请保持82字以内)</span>\
                </td>\
            </tr>\
        </table>\
        <table class="con_table">\
            <tr>\
                <th>文章2图片<em class="colorred">*</em></th>\
                <td>\
                    <span class="input_area">\
                        <div class="btn coupon_btn">\
                           <span>选择图片</span>\
                           <input  type="file" name="upload" >\
                           <input type="hidden" value="" name="module[3]['+i+'][2][pic]" class="pic_path noempty" data-empty="图文3文章2图片">\
                        </div>\
                        <img src="" class="show_img" width="30" height="30" style="display:none" />\
                    </span>\
                    <span>(尺寸960*330)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章2标题<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[3]['+i+'][2][tit]" class="noempty" data-empty="图文3文章2标题">\
                    <span>(请保持14字以内)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章2链接<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[3]['+i+'][2][link]" class="noempty bhttp" data-empty="图文3文章2链接">\
                    <span>(请以http://开头)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章2简介<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <textarea name="module[3]['+i+'][2][art]" class="noempty" data-empty="图文3文章2简介"></textarea>\
                    <span>(请保持82字以内)</span>\
                </td>\
            </tr>\
        </table>\
        <table class="con_table">\
            <tr>\
                <th>文章3图片<em class="colorred">*</em></th>\
                <td>\
                    <span class="input_area">\
                        <div class="btn coupon_btn">\
                           <span>选择图片</span>\
                           <input  type="file" name="upload" >\
                           <input type="hidden" value="" name="module[3]['+i+'][3][pic]" class="pic_path noempty" data-empty="图文3文章3图片">\
                        </div>\
                        <img src="" class="show_img" width="30" height="30" style="display:none" />\
                    </span>\
                    <span>(尺寸960*330)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章3标题<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[3]['+i+'][3][tit]" class="noempty" data-empty="图文3文章3标题">\
                    <span>(请保持14字以内)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章3链接<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[3]['+i+'][3][link]" class="noempty bhttp" data-empty="图文3文章3链接">\
                    <span>(请以http://开头)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章3简介<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <textarea name="module[3]['+i+'][3][art]" class="noempty" data-empty="图文3文章3简介"></textarea>\
                    <span>(请保持82字以内)</span>\
                </td>\
            </tr>\
        </table>\
        <table class="con_table">\
            <tr>\
                <th>文章4图片<em class="colorred">*</em></th>\
                <td>\
                    <span class="input_area">\
                        <div class="btn coupon_btn">\
                           <span>选择图片</span>\
                           <input  type="file" name="upload" >\
                           <input type="hidden" value="" name="module[3]['+i+'][4][pic]" class="pic_path noempty" data-empty="图文3文章4图片">\
                        </div>\
                        <img src="" class="show_img" width="30" height="30" style="display:none" />\
                    </span>\
                    <span>(尺寸960*330)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章4标题<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[3]['+i+'][4][tit]" class="noempty" data-empty="图文3文章4标题">\
                    <span>(请保持14字以内)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章4链接<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[3]['+i+'][4][link]" class="noempty bhttp" data-empty="图文3文章4链接">\
                    <span>(请以http://开头)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章4简介<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <textarea name="module[3]['+i+'][4][art]" class="noempty" data-empty="图文3文章4简介"></textarea>\
                    <span>(请保持82字以内)</span>\
                </td>\
            </tr>\
        </table>\
    </div>';
    /*图文4模块html*/
	var tuwen4 = '<div class="tuwen4 contnetbox module4" id="module_4_'+i+'">\
        <div class="con_title clearfix">\
            <h3>图文4</h3>\
            <p>序号：<input type="text" name="module[4]['+i+'][modid]" class="noempty isnum" data-empty="图文4模块序号"><em class="colorred">*</em></p>\
            <input type="button" value="删除模块" class="removemodule">\
        </div>\
        <div class="con_slt">\
            <img src="../../../Public/Admin/Images/images/tuwen4_s.png" height="275" width="784">\
        </div>\
        <table class="con_table">\
            <tr>\
                <th>图片<em class="colorred">*</em></th>\
                <td>\
                    <span class="input_area">\
                        <div class="btn coupon_btn">\
                           <span>选择图片</span>\
                           <input  type="file" name="upload" >\
                           <input type="hidden" value="" name="module[4]['+i+'][0][pic]" class="pic_path noempty" data-empty="图文4图片">\
                        </div>\
                        <img src="" class="show_img" width="30" height="30" style="display:none" />\
                    </span>\
                    <span>(尺寸320*410)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>图片标签<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[4]['+i+'][0][tab]" class="noempty" data-empty="图文4图片标签">\
                    <span>(请保持16字以内)</span>\
                </td>\
            </tr>\
        </table>\
        <table class="con_table">\
            <tr>\
                <th>文章1标题<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[4]['+i+'][1][tit]" class="noempty" data-empty="图文4文章1标题">\
                    <span>(请保持14字以内)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章1链接</th>\
                <td class="con_link">\
                    <input type="text" name="module[4]['+i+'][1][link]" class="bhttp">\
                    <span>(请以http://开头)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章1简介<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <textarea name="module[4]['+i+'][1][art]" class="noempty" data-empty="图文4文章1简介"></textarea>\
                    <span>(请保持66字以内)</span>\
                </td>\
            </tr>\
        </table>\
        <table class="con_table">\
            <tr>\
                <th>文章2标题<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[4]['+i+'][2][tit]" class="noempty" data-empty="图文4文章2标题">\
                    <span>(请保持14字以内)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章2链接</th>\
                <td class="con_link">\
                    <input type="text" name="module[4]['+i+'][2][link]" class="bhttp">\
                    <span>(请以http://开头)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章2简介<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <textarea name="module[4]['+i+'][2][art]" class="noempty" data-empty="图文4文章2简介"></textarea>\
                    <span>(请保持66字以内)</span>\
                </td>\
            </tr>\
        </table>\
        <table class="con_table">\
            <tr>\
                <th>文章3标题<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[4]['+i+'][3][tit]" class="noempty" data-empty="图文4文章3标题">\
                    <span>(请保持14字以内)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章3链接</th>\
                <td class="con_link">\
                    <input type="text" name="module[4]['+i+'][3][link]" class="bhttp">\
                    <span>(请以http://开头)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>文章3简介<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <textarea name="module[4]['+i+'][3][art]" class="noempty" data-empty="图文4文章3简介"></textarea>\
                    <span>(请保持66字以内)</span>\
                </td>\
            </tr>\
        </table>\
    </div>';
    /*大图模块html*/
	var bigimg = '<div class="bigimg contnetbox module5" id="module_5_'+i+'">\
        <div class="con_title clearfix">\
            <h3>图片</h3>\
            <p>序号：<input type="text" name="module[5]['+i+'][modid]" class="noempty isnum" data-empty="大图片模块序号"><em class="colorred">*</em></p>\
            <input type="button" value="删除模块" class="removemodule">\
        </div>\
        <div class="con_slt">\
            <img src="../../../Public/Admin/Images/images/bigimg.png" height="265" width="771">\
        </div>\
        <table class="con_table">\
            <tr>\
                <th>图片1<em class="colorred">*</em></th>\
                <td>\
                    <span class="input_area">\
                        <div class="btn coupon_btn">\
                           <span>选择图片</span>\
                           <input  type="file" name="upload" >\
                           <input type="hidden" value="" name="module[5]['+i+'][1][pic]" class="pic_path noempty" data-empty="大图片">\
                        </div>\
                        <img src="" class="show_img" width="30" height="30" style="display:none" />\
                    </span>\
                    <span>(宽度保持1000)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>图片1标签<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[5]['+i+'][1][tit]" class="noempty" data-empty="大图片1标签">\
                </td>\
            </tr>\
            <tr>\
                <th>图片1链接</th>\
                <td class="con_link">\
                    <input type="text" name="module[5]['+i+'][1][link]" class="bhttp">\
                    <span>(请以http://开头)</span>\
                </td>\
            </tr>\
        </table>\
    </div>';
    /*相册模块html*/
	var album = '<div class="album contnetbox module6" id="module_6_'+i+'">\
        <div class="con_title clearfix">\
            <h3>相册</h3>\
            <p>序号：<input type="text" name="module[6]['+i+'][modid]" class="noempty isnum" data-empty="相册模块序号"><em class="colorred">*</em></p>\
            <input type="button" value="删除模块" class="removemodule">\
        </div>\
        <div class="con_slt">\
            <img src="../../../Public/Admin/Images/images/album_s.png" height="242" width="603">\
        </div>\
        <table class="con_table">\
            <tr>\
                <th>图片1<em class="colorred">*</em></th>\
                <td>\
                    <span class="input_area">\
                        <div class="btn coupon_btn">\
                           <span>选择图片</span>\
                           <input  type="file" name="upload" >\
                           <input type="hidden" value="" name="module[6]['+i+'][1][pic]" class="pic_path noempty" data-empty="相册图片1">\
                        </div>\
                        <img src="" class="show_img" width="30" height="30" style="display:none" />\
                    </span>\
                    <span>(尺寸800*600)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>标题1内容<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[6]['+i+'][1][tit]" class="noempty" data-empty="相册标题1内容">\
                </td>\
            </tr>\
            <tr>\
                <th>图片2<em class="colorred">*</em></th>\
                <td>\
                    <span class="input_area">\
                        <div class="btn coupon_btn">\
                           <span>选择图片</span>\
                           <input  type="file" name="upload" >\
                           <input type="hidden" value="" name="module[6]['+i+'][2][pic]" class="pic_path noempty" data-empty="相册图片2">\
                        </div>\
                        <img src="" class="show_img" width="30" height="30" style="display:none" />\
                    </span>\
                    <span>(尺寸800*600)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>标题2内容<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[6]['+i+'][2][tit]" class="noempty" data-empty="相册标题2内容">\
                </td>\
            </tr>\
            <tr>\
                <th>图片3<em class="colorred">*</em></th>\
                <td>\
                    <span class="input_area">\
                        <div class="btn coupon_btn">\
                           <span>选择图片</span>\
                           <input  type="file" name="upload" >\
                           <input type="hidden" value="" name="module[6]['+i+'][3][pic]" class="pic_path noempty" data-empty="相册图片3">\
                        </div>\
                        <img src="" class="show_img" width="30" height="30" style="display:none" />\
                    </span>\
                    <span>(尺寸800*600)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>标题3内容<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[6]['+i+'][3][tit]" class="noempty" data-empty="相册标题3内容">\
                </td>\
            </tr>\
            <tr>\
                <th>图片4<em class="colorred">*</em></th>\
                <td>\
                    <span class="input_area">\
                        <div class="btn coupon_btn">\
                           <span>选择图片</span>\
                           <input  type="file" name="upload" >\
                           <input type="hidden" value="" name="module[6]['+i+'][4][pic]" class="pic_path noempty" data-empty="相册图片4">\
                        </div>\
                        <img src="" class="show_img" width="30" height="30" style="display:none" />\
                    </span>\
                    <span>(尺寸800*600)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>标题4内容<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[6]['+i+'][4][tit]" class="noempty" data-empty="相册标题4内容">\
                </td>\
            </tr>\
            <tr>\
                <th>图片5<em class="colorred">*</em></th>\
                <td>\
                    <span class="input_area">\
                        <div class="btn coupon_btn">\
                           <span>选择图片</span>\
                           <input  type="file" name="upload" >\
                           <input type="hidden" value="" name="module[6]['+i+'][5][pic]" class="pic_path noempty" data-empty="相册图片5">\
                        </div>\
                        <img src="" class="show_img" width="30" height="30" style="display:none" />\
                    </span>\
                    <span>(尺寸800*600)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>标题5内容<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[6]['+i+'][5][tit]" class="noempty" data-empty="相册标题5内容">\
                </td>\
            </tr>\
        </table>\
    </div>';
    /*标题模块html*/
	var moduletitle = '<div class="moduletitle contnetbox module7" id="module_7_'+i+'">\
                <div class="con_title clearfix">\
                    <h3>标题</h3>\
                    <p>序号：<input type="text" name="module[7]['+i+'][modid]" class="noempty isnum" data-empty="标题模块序号"><em class="colorred">*</em></p>\
                    <input type="button" value="删除模块" class="removemodule">\
                </div>\
                <div class="con_slt">\
                    <img src="../../../Public/Admin/Images/images/title.png" height="41" width="322">\
                </div>\
                <table class="con_table">\
                    <tr>\
                        <th>模块大标题内容<em class="colorred">*</em></th>\
                        <td class="con_link">\
                            <input type="text" name="module[7]['+i+'][1][tit]" class="noempty" data-empty="标题内容">\
                            <span>(请保持在14字以内)</span>\
                        </td>\
                    </tr>\
                    <tr>\
                        <th>标题位置<em class="colorred">*</em></th>\
                        <td class="tit_place">\
                            <p><input type="radio" value="1" checked="true" name="module[7]['+i+'][align]">居左</p>\
                            <p><input type="radio" value="2" name="module[7]['+i+'][align]">居中</p>\
                            <p><input type="radio" value="3" name="module[7]['+i+'][align]">居右</p>\
                        </td>\
                    </tr>\
                </table>\
            </div>';
    /*店铺模块html*/
	var store = '<div class="store contnetbox module8" id="module_8_'+i+'">\
        <div class="con_title clearfix">\
            <h3>店铺</h3>\
            <p>序号：<input type="text" name="module[8]['+i+'][modid]" class="noempty isnum" data-empty="店铺模块序号"><em class="colorred">*</em></p>\
            <input type="button" value="删除模块" class="removemodule">\
        </div>\
        <div class="con_slt">\
            <img src="../../../Public/Admin/Images/images/store.png" height="165" width="668">\
        </div>\
        <table class="con_table">\
            <tr>\
                <th>店铺1 ID<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[8]['+i+'][1][link]" class="noempty isnum" data-empty="店铺1ID">\
                </td>\
            </tr>\
            <tr>\
                <th>店铺1标题</th>\
                <td class="con_link">\
                    <input type="text" name="module[8]['+i+'][1][tit]" placeholder="店铺1标题">\
                    <span>(选填，请保持在15字以内)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>店铺2 ID<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[8]['+i+'][2][link]" class="noempty isnum" data-empty="店铺2ID">\
                </td>\
            </tr>\
            <tr>\
                <th>店铺2标题</th>\
                <td class="con_link">\
                    <input type="text" name="module[8]['+i+'][2][tit]" placeholder="店铺2标题">\
                    <span>(选填，请保持在15字以内)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>店铺3 ID<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[8]['+i+'][3][link]" class="noempty isnum" data-empty="店铺3ID">\
                </td>\
            </tr>\
            <tr>\
                <th>店铺3标题</th>\
                <td class="con_link">\
                    <input type="text" name="module[8]['+i+'][3][tit]" placeholder="店铺3标题">\
                    <span>(选填，请保持在15字以内)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>店铺4 ID<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[8]['+i+'][4][link]" class="noempty isnum" data-empty="店铺4ID">\
                </td>\
            </tr>\
            <tr>\
                <th>店铺4标题</th>\
                <td class="con_link">\
                    <input type="text" name="module[8]['+i+'][4][tit]" placeholder="店铺4标题">\
                    <span>(选填，请保持在15字以内)</span>\
                </td>\
            </tr>\
        </table>\
    </div>';
    /*商品模块html*/
	var goods = '<div class="goods contnetbox module9" id="module_9_'+i+'">\
        <div class="con_title clearfix">\
            <h3>商品</h3>\
            <p>序号：<input type="text" name="module[9]['+i+'][modid]" class="noempty isnum" data-empty="商品模块序号"><em class="colorred">*</em></p>\
            <input type="button" value="删除模块" class="removemodule">\
        </div>\
        <div class="con_slt">\
            <img src="../../../Public/Admin/Images/images/goods_s.png" height="258" width="807">\
        </div>\
        <table class="con_table">\
            <tr>\
                <th>商品1 ID<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[9]['+i+'][1][link]" class="noempty isnum" data-empty="商品1ID">\
                </td>\
            </tr>\
            <tr>\
                <th>商品1标题</th>\
                <td class="con_link">\
                    <input type="text" name="module[9]['+i+'][1][tit]" placeholder="商品1标题">\
                    <span>(选填，请保持在15字以内)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>商品2 ID<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[9]['+i+'][2][link]" class="noempty isnum" data-empty="商品2ID">\
                </td>\
            </tr>\
            <tr>\
                <th>商品2标题</th>\
                <td class="con_link">\
                    <input type="text" name="module[9]['+i+'][2][tit]" placeholder="商品2标题">\
                    <span>(选填，请保持在15字以内)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>商品3 ID<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[9]['+i+'][3][link]" class="noempty isnum" data-empty="商品3ID">\
                </td>\
            </tr>\
            <tr>\
                <th>商品3标题</th>\
                <td class="con_link">\
                    <input type="text" name="module[9]['+i+'][3][tit]" placeholder="商品3标题">\
                    <span>(选填，请保持在15字以内)</span>\
                </td>\
            </tr>\
            <tr>\
                <th>商品4 ID<em class="colorred">*</em></th>\
                <td class="con_link">\
                    <input type="text" name="module[9]['+i+'][4][link]" class="noempty isnum" data-empty="商品4ID">\
                </td>\
            </tr>\
            <tr>\
                <th>商品4标题</th>\
                <td class="con_link">\
                    <input type="text" name="module[9]['+i+'][4][tit]" placeholder="商品4标题">\
                    <span>(选填，请保持在15字以内)</span>\
                </td>\
            </tr>\
        </table>\
    </div>';
    var ohtml = '';
    if( ind == 1){
    	ohtml = tuwen1;
    }else if( ind == 2){
    	ohtml = tuwen2;
    }else if( ind == 3){
    	ohtml = tuwen3;
    }else if( ind == 4){
    	ohtml = tuwen4;
    }else if( ind == 5){
    	ohtml = bigimg;
    }else if( ind == 6){
    	ohtml = album;
    }else if( ind == 7){
    	ohtml = moduletitle;
    }else if( ind == 8){
    	ohtml = store;
    }else if( ind == 9){
    	ohtml = goods;
    }
    $('.modulecontent').append(ohtml);
}
/*添加删除模块*/
$(function() {
	/*添加模块*/
	var i;
	$('.addmodule input').on('click',function(){
	    var ind = parseInt($('.modulechose input:checked').parent().index())+1;
	    /*取模块的id*/
		var idStr = $('.module'+ind+':last').attr('id');
		/*如果还没有这个模块，则i=1*/
		if( idStr === undefined){
			i = 1;
			addmodule(ind,i);
		}else{
			/*取模块id的最后一位数字并加1作为后一个同模块id最后一位数字*/
			//i = parseInt(idStr.substring(idStr.length-2))+1;
            i = parseInt(idStr.substring(idStr.lastIndexOf('_')+1))+1;
			addmodule(ind,i);
		}
	});
	/*删除模块*/
	$('.modulecontent').delegate(('.removemodule'),'click',function(){
	    $(this).parent().parent().remove();
	});
})

$(function() {
    /*标题位置切换*/
    $('.tit_place p').click(function() {
        $(this).find('input').attr('checked',true);
    });

    /*选择添加的模块*/
    $('.modulechose p').click(function() {
        $(this).find('input').attr('checked',true);
        var i = $('.modulechose input:checked').parent().index();
        $('.previewimg img:eq('+i+')').show().siblings().hide();
    });
    /*添加导航栏*/
    $('.addnavmodule').change(function () {
       if($(this).is(':checked')){
            $('.nav').show().find('tr:visible').show();
            navbtnnum();
       }else{
            $('.nav').hide();
            navbtnnum();
       }
    });
    function addnavbtn(i) {
        /*添加导航栏按钮html*/
        var navbtn = '<tr class="navbtn'+i+'">\
            <th>添加导航栏按钮'+i+'链接<em class="colorred">*</em></th>\
            <td class="con_link3">\
                <input class="txtwrite noempty bhttp" type="text" name="module[0][1]['+i+'][link]" data-empty="导航栏按钮'+i+'链接">\
                <span>(请以http://开头)</span>\
                <input class="navremove" id="navremove_'+i+'" type="button" value="&nbsp;&nbsp;删除&nbsp;&nbsp;">\
            </td>\
        </tr>\
        <tr class="navbtn navbtn'+i+'" id="navbtn_'+i+'">\
            <th>导航栏按钮'+i+'文字<em class="colorred">*</em></th>\
            <td class="con_link3">\
                <input type="text" name="module[0][1]['+i+'][tit]" class="noempty" data-empty="导航栏按钮'+i+'文字">\
                <span>(请保持在6字以内)</span>\
            </td>\
        </tr>';
        $('#navbtn_'+(i-1)+'').after(navbtn);
    }
    /*添加导航按钮*/
    $('.addbtn').on('click',function() {
        var navnum = $('.navbtn').length;
        var navid = $('.navbtn:last').attr('id');
        var navi = parseInt(navid.substring(navid.lastIndexOf('_')+1));
        if( navi == 3 ){
            addnavbtn(4);
        }else if( navi == 4 ){
            addnavbtn(5);
        }else if( navi == 5 && navnum != 5 ){
            addnavbtn(4);
        }else if( navi == 5 && navnum == 5 ){
            alert('导航按钮最多为5个！');
        }
    });
    /*删除导航栏按钮*/
    $('.navmodule').delegate(('.navremove'),'click',function() {
        var removeid = $(this).attr('id');
        var removei = parseInt(removeid.substring(removeid.lastIndexOf('_')+1))
        $('.navbtn'+removei+'').remove();
    });
    /*固定模块模式选择*/
    $('.gdmk1').change(function () {
        if($(this).is(':checked')){
            $('.questionnaire1').attr('disabled',false);
            if( $('.gdmk2').is(':checked') ){
                $('.yl').attr({'disabled':false});
                if( $('.yl').is(':checked') ){
                    $('.qr').attr({'disabled':false,'checked':true});
                    $('.fc').attr('disabled',false);
                }else if( $('.el').is(':checked') ){
                    $('.fc').attr({'disabled':false,'checked':true});
                    $('.qr').attr('disabled',false);
                }else{
                    $('.qr').attr({'disabled':false,'checked':false});
                    $('.fc').attr('disabled',false);
                }
            }else {
                $('.qr').attr({'disabled':false,'checked':true});
                $('.fc').attr('disabled',false);
            }
        }else{
            $('.questionnaire1').attr('disabled',true);
            $('.qr').attr({'disabled':true,'checked':false});
            $('.fc').attr('disabled',true);
            if($('.gdmk2').is(':checked')){
                $('.yl').attr({'disabled':true});
                $('.el').attr({'disabled':false,'checked':true});
            }
        }
    });
    $('.gdmk2').change(function () {
        if($(this).is(':checked')){
            $('.questionnaire2').attr('disabled',false);
            if( $('.gdmk1').is(':checked') ){
                $('.questionnaire1').attr('disabled',false);
                if( $('.qr').is(':checked') ){
                    $('.yl').attr({'disabled':false,'checked':true});
                    $('.el').attr('disabled',false);
                }else if( $('.fc').is(':checked') ){
                    $('.el').attr({'disabled':false,'checked':true});
                    $('.yl').attr('disabled',false);
                }else{
                    $('.yl').attr({'disabled':false,'checked':false});
                    $('.el').attr('disabled',false);
                }
            }else{
                $('.yl').attr({'disabled':true});
                $('.el').attr({'disabled':false,'checked':true});
            }
        }else{
            $('.questionnaire2').attr('disabled',true);
            $('.yl').attr({'disabled':true,'checked':false});
            $('.el').attr('disabled',true);
        }
    });
    $('.con_link2 span').click(function() {
        if(!$(this).find('input').is(':disabled')){
            $(this).find('input').attr('checked',true);
            if( $('.qr').is(':checked') && !$('.yl').is(':disabled')  ){
                $('.yl').attr('checked',true);
            }else if( $('.fc').is(':checked') && !$('.el').is(':disabled') ){
                $('.el').attr('checked',true);
            }
        }
    });
    $('.con_link4 span').click(function() {
        if(!$(this).find('input').is(':disabled')){
            $(this).find('input').attr('checked',true);
            if( $('.yl').is(':checked') && !$('.qr').is(':disabled') ){
                $('.qr').attr('checked',true);
            }else if( $('.el').is(':checked') && !$('.fc').is(':disabled') ){
                $('.fc').attr('checked',true);
            }
        }
    });
    /*提交表单判断必填项是否为空*/
    $('#submitform').on('click',function proofread() {
        for (var i = 0; i <= $('.noempty:enabled').length-1 ; i++) {
            var oinput = $('.noempty:enabled:eq('+i+')');
            var sval = oinput.val();//获取input的内容
            sval = $.trim(sval);//去除内容的前后空格
            if( oinput.is(':visible') ){
                if( sval.length == 0 ){
                    alert( oinput.attr('data-empty') + '不能为空！');
                    oinput.focus();
                    return false;
                }else if( oinput.hasClass('isnum') ){
                    var regnum = /^[0-9]*$/g;
                    if( !regnum.test(sval) ){
                        alert( oinput.attr('data-empty') + '必须为数字！');
                        oinput.focus();
                        return false;
                    }
                }else if( oinput.hasClass('bhttp') ){
                    var reghttp = /^http:\/\//;
                    if( !reghttp.test(sval) ){
                        alert('链接必须以http://开头！');
                        oinput.focus();
                        return false;
                    }
                }
            }
        };
		for (var n = 0; n <= $('.noempty[type=hidden]').length-1 ; n++) {
            var oimg = $('.noempty[type=hidden]:eq('+n+')');
			var simg = oimg.val();
			if( simg.length == 0 ){
				alert( oimg.attr('data-empty') + '不能为空！');
				oimg.focus();
				return false;
			}
        };
		$('#pageform').submit();
    });
	
});


/*图片上传*/
upload({
	btn:"coupon_btn",//外层class
    showimg:true,//显示图片
    action:'/iadmin.php/Upload/imageUpload',//上传地址/iadmin.php/Upload/imageUpload
	type:'subtem' //上传类型
});
