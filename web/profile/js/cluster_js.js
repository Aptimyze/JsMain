function search_clustering(item,value,item_bread_crumb)
{
        document.form_search_clustering.item.value=item;
        document.form_search_clustering.value.value=value;
        document.form_search_clustering.bread_crumb_hidden.value=item_bread_crumb;
        document.form_search_clustering.submit();
}

