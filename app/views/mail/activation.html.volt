Hey
{% if user %}
	<a href="#{{user.id}}">{{user.nickname}}</a>
{% endif %}