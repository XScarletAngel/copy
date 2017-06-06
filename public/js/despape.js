$(function(){
  // 添加题型
  $('.fixed_nav li').bind('click',function(){
    var newsClone = $('.disbox .design_content').clone();
    var dataNum = $(this).attr('data');
    var index = $(this).index();
    var thisName = $(this).text();
    var thisId = $(this).attr('data-name');
    var thisTitle = $(this).attr('data-title');
    var thisInfo = $(this).attr('data-info');
    var thisListId = $(this).attr('data-list-id');
    var thisListDiff = $(this).attr('data-list-diff');
    var thisListScore = $(this).attr('data-list-score')
    if(dataNum == 'false'){
      $(this).attr('data','true');
      $(this).find('i').removeClass().addClass('icon-ok');
      $('.fixed_footer').before(newsClone);
      newObj($('.design_content .newInput'));
      $('.design_pape .design_content:last').find('h5').text(thisName);
      $('.design_pape .design_content:last').find('.newInput').attr('name',thisId);
      $('.design_pape .design_content:last').find('.title_mode').attr('name',thisTitle);
      $('.design_pape .design_content:last').find('.info_mode').attr('name',thisInfo);
      $('.design_pape .design_content:last').find('.add_test').attr('data-list-id',thisListId);
      $('.design_pape .design_content:last').find('.add_test').attr('data-list-diff',thisListDiff);
      $('.design_pape .design_content:last').find('.add_test').attr('data-list-score',thisListScore)
    }
  });
  // 题型删除
  $('.design_pape').on('click','.deleteBtn',function(){
    var thisTitle = $(this).parent().prev().text();
    $('.fixed_nav li').each(function(){
      var rightNav = $(this).text();
      if(thisTitle == rightNav){
        $(this).find('i').removeClass().addClass('icon-plus');
        $(this).attr('data','false')
      }
    })
    $(this).parents('.design_content').remove();
    newObj($('.design_content .newInput'));
    layer.msg('删除成功')
  })
  // 删除题目
  $('.design_pape').on('click','.test_delect',function(){
    var thisTable = $(this).parents('table')
    $(this).parents('tr').remove();
    newNum(thisTable.find('.test_number'))
  });
  // 上移题目
  $('.design_pape').on('click','.text_prev',function(){
    var onthis = $(this).parent().parent();
    var getUp = onthis.prev();
    if($(getUp).has("input:text").size()==0){
       return;
    };
    $(onthis).after(getUp);
    newNum($(this).parents('table').find('.test_number'))
  });
  // 下移题目
  $('.design_pape').on('click','.text_next',function(){
    var onthis = $(this).parent().parent();
    var getDown = onthis.next();
    if($(getDown).has("input:text").size()==0){
       return;
    };
    $(getDown).after(onthis);
    newNum($(this).parents('table').find('.test_number'))
  });
  // 全选全不选
  $('.design_pape').on('click','table th input:checkbox',function(){
    var checkTrue = this.checked;
    if(checkTrue){
      $(this).parents('table').find('input[name=subBox]').prop('checked',true);
      $(this).parents('table').find('span').addClass('checked');
    }else{
      $(this).parents('table').find('input[name=subBox]').prop('checked',false)
      $(this).parents('table').find('span').removeClass('checked');
    }
  });
  // 题型上移
  $('.design_pape').on('click','.upBtn',function(){
    var onthis = $(this).parents('.design_content');
    var getUp = onthis.prev();
    if($(getUp).has("h5").size()==0){
       return;
    }
    $(onthis).after(getUp);
    newObj($('.design_content .newInput'));
  });
  // 题型下移
  $('.design_pape').on('click','.downBtn',function(){
    var onthis = $(this).parents('.design_content');
    var getUp = onthis.next();
    if($(getUp).has("h5").size()==0){
       return;
    }
    $(getUp).after(onthis);
    newObj($('.design_content .newInput'));
  });
  //添加试题
  $('.design_pape').on('click','.add_test',function(){
    var html = $('#addtest').val();
    var me = $(this);
    var thislistNameId = $(this).attr('data-list-id');//题目IDname名称
    var thislistNameDiff = $(this).attr('data-list-diff');//题目难度name名称
    var thislistNameScore = $(this).attr('data-list-score');//题目分数name名称
      //添加习题题型参数
    if(thislistNameId == 'danxlistId[]'){html = html+"&ce_type=1"; }
    if(thislistNameId == 'duoxlistId[]'){html = html+"&ce_type=2"; }
    if(thislistNameId == 'budxlistId[]'){html = html+"&ce_type=3"; }
    if(thislistNameId == 'pandlistId[]'){html = html+"&ce_type=4"; }

    var index = layer.open({
        type: 2,
        title: '添加习题',
        skin: 'layui-layer-rim', //加上边框
        area: ['700px', '600px'], //宽高
        btn:'确认' ,
        content: html,
        yes:function(){
          var body = layer.getChildFrame('table');
          body.find(':checkbox').each(function(){
            var checked = $(this).attr('checked')
            if(checked == 'checked'){
              var listId = $(this).val();//题目ID
              var difficulty = $(this).attr('data')//题目难度
              var score = $(this).parents('tr').find('.score').text();//题目分数
              var html = '<tr>';
                  html+= '<input name="'+thislistNameId+'" type="hidden" value="'+listId+'"/><input name="'+thislistNameDiff+'" type="hidden" value="'+difficulty+'">';
                  html+='<td class="test_number">1</td><td><input name="'+thislistNameScore+'" type="text" value="'+score+'"></td>';
                  html+='<td><a class="text_prev" href="javascript:;">上移</a><a class="text_next" href="javascript:;">下移</a><a class="test_delect" href="javascript:;">删除</a></td></tr>';
              me.parents('.design_content').find('.table').append(html);
            };
          });
          var meparenttable = me.parents('.design_content').find('.test_number');
          newNum(meparenttable)
          layer.close(index);
        }
    });
  })
})
// 题目序号重新排序
function newNum(obj){
  var n=1;
  obj.each(function(){
    $(this).text(n++);
  })
}
// 题型序号排序
function newObj(obj){
  var m=1;
  obj.each(function(){
    $(this).val(m++)
  })
}
