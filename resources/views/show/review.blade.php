@extends('show.layouts.app')
@section('content')
<iframe id="appFrame" src="{{$url}}" height="100%" width="100%" allow="camera *;microphone *;fullscreen" allowfullscreen=true allowusermedia=true></iframe>
<script type="text/javascript">
    jQuery(function () {
        jQuery('#appFrame').height(jQuery(window).height());
    });
</script>
<style type="text/css">
    body{padding: 0px; margin: 0px;}
    iframe{border: none;}
</style>
@endsection