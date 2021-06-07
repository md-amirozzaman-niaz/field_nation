select user.*,
       (select avg(test_result.correct) from test_result where test_result.user_id = user.user_id) as avg,
       (select max(test_result.time_taken) from test_result where test_result.user_id = user.user_id) as latest
from user;