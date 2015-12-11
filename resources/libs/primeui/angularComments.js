// AngularJS specific code
function commentControl($scope)
{
    $scope.commentator = function()
    {
        var result = $scope.yourName;
        if (!$scope.yourName)
        {
            result = "Anonymous";
        }
        if ($scope.website)
        {
            result += " (" + $scope.website + ")";
        }
        return result;
    };
    $scope.commentPreview = function()
    {
        var result = $scope.comment;
        if (!$scope.comment)
        {
            result = "Nothing yet.";
        }
        return result;
    };
    $scope.previewVisible = function()
    {
        if (!$scope.yourName)
            if (!$scope.website)
                if (!$scope.comment)
                    return "none";
        return "block";
    }
}
 
// Primefaces specific code
// decorate the comment form's input fields
$(function()
{
    $('#in').puiinputtext();
    $('#commentatorsNameID').puiinputtext();
    $('#commentatorsWebSiteID').puiinputtext();
    $('#commentID').puiinputtextarea({
        counter : $('#restZeichenID'),
        counterTemplate : '{0} characters remaining.',
        maxlength : 1000
    });
});
 
// define the comment dialog
$(function()
{
    $('#dlg').puidialog({
        showEffect : 'fade',
        hideEffect : 'fade',
        minimizable : true,
        maximizable : true,
        width       : 600,
        modal : true,
        location : 'top',
        buttons : [ {
            text : 'Submit',
            icon : 'ui-icon-check',
            click : function()
            {
                $('#dlg').puidialog('hide');
            }
        }, {
            text : 'Forget about my comment',
            icon : 'ui-icon-close',
            click : function()
            {
                $('#dlg').puidialog('hide');
            }
        } ]
    });
});
// decorate the button opening the comments dialog
$(function()
{
    $('#btn-show').puibutton({
        icon : 'ui-icon-arrow-4-diag',
        click : function()
        {
            $('#dlg').puidialog('show');
        }
    });
});
