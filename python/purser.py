import ctypes

ctypes.windll.shell32.Shell32ExecuteA(0, 'open', '실행할 프로그램의 경로', '프로그램을 실행하면서 넣을 파일의 경로', None, 1)
